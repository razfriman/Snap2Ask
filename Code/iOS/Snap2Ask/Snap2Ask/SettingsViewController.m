//
//  SettingsViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/28/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "SettingsViewController.h"

@interface SettingsViewController ()
@property (weak, nonatomic) IBOutlet UILabel *emailLabel;
@property (weak, nonatomic) IBOutlet UILabel *balanceLabel;

@property (weak, nonatomic) IBOutlet UILabel *questionsAskedLabel;
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
            return 1;
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

- (void) refreshView:(UIRefreshControl*) sender
{
    int userId = [UserInfo sharedClient].userModel.userId;
    
    [[Snap2AskClient sharedClient] loadUserInfo:userId];
    
    [sender endRefreshing];
}


@end
