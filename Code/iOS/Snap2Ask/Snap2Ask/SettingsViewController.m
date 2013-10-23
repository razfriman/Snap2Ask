//
//  SettingsViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/28/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "SettingsViewController.h"
#import "MBProgressHUD.h"

#define DELETE_CONFIRMATION 1

@interface SettingsViewController ()

@property (weak, nonatomic) IBOutlet UILabel *emailLabel;
@property (weak, nonatomic) IBOutlet UILabel *balanceLabel;

@property (weak, nonatomic) IBOutlet UILabel *questionsAskedLabel;

@property (strong, nonatomic) MBProgressHUD *hud;

@end

@implementation SettingsViewController

- (id)initWithStyle:(UITableViewStyle)style
{
    self = [super initWithStyle:style];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];

    self.clearsSelectionOnViewWillAppear = NO;
    
    [self.refreshControl addTarget:self action:@selector(refreshView:) forControlEvents:UIControlEventValueChanged];
    
    // Register for notifications
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(userInfoUpdated:) name:UserInfoUpdatedNotification object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(userDeleted:) name:UserDeletedNotification object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(balanceUpdated:) name:BalanceUpdatedNotification object:nil];
    
    
    // Update the UI
    [self userInfoUpdated:nil];

    
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void) userInfoUpdated:(NSNotification *)notification
{
    UserModel *userInfo = [UserInfo sharedClient].userModel;
    
    _emailLabel.text = userInfo.email;
    _balanceLabel.text = [[NSNumber numberWithInteger:userInfo.balance ] stringValue];

    //_questionsAskedLabel.text = userInfo.questionsAsked;
}

- (void) userDeleted:(NSNotification *)notification
{
    if (_hud) {
        // Hide the indicator
        [_hud hide:YES];
    }
    
    // Logout
    [self performSegueWithIdentifier:@"unwindToMainSegue" sender:self];
}

- (void) balanceUpdated:(NSNotification *)notification
{
    
    // TODO: Get amount added from notification.userData
    
    [[[UIAlertView alloc] initWithTitle:@"Success"
                                message:@"Added SnapCash"
                               delegate:nil
                      cancelButtonTitle:@"OK"
                      otherButtonTitles:nil] show];
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 3;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    switch (section) {
        case 0:
            return 2;
        case 1:
            return 1;
        case 2:
            return 2;
        default:
            return 0;
    }
}


- (void) prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    if ([segue.identifier isEqualToString:@"unwindToMainSegue"]) {
        
        KeychainItemWrapper *keychainAuthenticationMode = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask_AuthenticationMode" accessGroup:nil];
        NSString *authenticationMode = [keychainAuthenticationMode objectForKey:(__bridge NSString*)kSecAttrAccount];
        
        
        if ([authenticationMode isEqualToString:@"facebook"]) {
            
            if ([FBSession.activeSession isOpen]) {
                [FBSession.activeSession closeAndClearTokenInformation];
            }
        } else if ([authenticationMode isEqualToString:@"google"]) {
            
            if ([[GPPSignIn sharedInstance] authentication]) {
                
                [[GPPSignIn sharedInstance] disconnect];
            }
        } else if ([authenticationMode isEqualToString:@"custom"]) {
            KeychainItemWrapper *keychainItem = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask" accessGroup:nil];
            NSString *email = [keychainItem objectForKey:(__bridge NSString*)kSecAttrAccount];
            NSString *password = [keychainItem objectForKey:(__bridge NSString*)kSecValueData];
            
            if (email && password) {
                // CLEAR THE KEYCHAIN DATA
                [keychainItem resetKeychainItem];
            }
        }
        
        // Reset the saved authentication mode
        [keychainAuthenticationMode resetKeychainItem];
        
        
        int userId = [UserInfo sharedClient].userModel.userId;
        NSString * userChannel = [NSString stringWithFormat:@"user_%d", userId];
        PFInstallation *currentInstallation = [PFInstallation currentInstallation];
        [currentInstallation removeObject:userChannel forKey:@"channels"];
        [currentInstallation saveInBackground];
    }
}

- (void) tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    if (indexPath.section == 0 && indexPath.row == 1) {
        
        UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:nil delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Add 10 SnapCash", @"Add 50 SnapCash", nil];
    
        [actionSheet showInView:self.view];

    } else if (indexPath.section == 2 && indexPath.row == 1) {
        
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Warning"
                                                        message:@"Are you sure you want to permanently delete your account?"
                                                       delegate:self
                                              cancelButtonTitle:@"Cancel"
                                              otherButtonTitles:@"DELETE", nil];
        alert.tag = DELETE_CONFIRMATION;
        [alert show];
    }
    
    [tableView deselectRowAtIndexPath:indexPath animated:YES];
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (alertView.tag == DELETE_CONFIRMATION && buttonIndex == 1) {

        
        if (!_hud) {
            
            // Show the indicator            
            _hud = [MBProgressHUD showHUDAddedTo:self.view animated:YES];
            _hud.mode = MBProgressHUDModeIndeterminate;
            _hud.labelText = @"Deleting Account";
            _hud.removeFromSuperViewOnHide = YES;
        }
        // Delete Account
        [[Snap2AskClient sharedClient] deleteUser:[UserInfo sharedClient].userModel.userId];
    }
}

#pragma mark - Action Sheet
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex {
    if (buttonIndex == 0) {
        
        // Add 10
        [[Snap2AskClient sharedClient] updateSnapCash:10 forUser:[UserInfo sharedClient].userModel.userId];
        
    } else if (buttonIndex == 1) {
        
        // Add 50
        [[Snap2AskClient sharedClient] updateSnapCash:10 forUser:[UserInfo sharedClient].userModel.userId];

    } else if (buttonIndex == 2) {
        // Cancel button
        return;
    }
}

- (void) refreshView:(UIRefreshControl*) sender
{
    int userId = [UserInfo sharedClient].userModel.userId;
    
    [[Snap2AskClient sharedClient] loadUserInfo:userId];
    
    [sender endRefreshing];
}


@end
