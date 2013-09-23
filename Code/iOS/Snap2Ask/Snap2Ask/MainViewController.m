//
//  MainViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "MainViewController.h"
#import "Snap2AskClient.h"
#import <FacebookSDK/FacebookSDK.h>

@interface MainViewController () <FBLoginViewDelegate>

@property (weak, nonatomic) IBOutlet UITextField *usernameTextField;
@property (weak, nonatomic) IBOutlet UITextField *passwordTextField;
@property (weak, nonatomic) IBOutlet UIButton *customSignInButton;
@property (weak, nonatomic) IBOutlet FBLoginView *facebookSignInButton;
@property (weak, nonatomic) IBOutlet UIButton *googleSignInButton;
@property (weak, nonatomic) IBOutlet UIButton *registerButton;

@end

@implementation MainViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view.
    
    //[[Snap2AskClient sharedClient] getQuestionsModel:10];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    if(textField == _usernameTextField) {
        [_passwordTextField becomeFirstResponder];
    } else if (textField == _passwordTextField) {
        [_passwordTextField resignFirstResponder];
        [self login];
    }
    
    return YES;
}

- (void)login {
    
    
    BOOL validLogin = NO;
    
    if ([_usernameTextField.text isEqualToString:@""]) {
        // Please enter a username
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter a username"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
        
        return;
    } else if ([_passwordTextField.text isEqualToString:@""]) {
        // Please enter a password
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter a password"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
        
        return;
    } else {
        
        // TODO: VALIDATE LOGIN
        //CHECK LOGIN
        //DEBUG:
        validLogin = YES;
        
    }
    
    
    if (validLogin) {
        
        // Reset password field
        _passwordTextField.text = @"";
        
        [self performSegueWithIdentifier:@"loginSegue" sender:self];
    }
}

// FACEBOOK STUFF
- (void)loginViewShowingLoggedInUser:(FBLoginView *)loginView {

}
- (IBAction)loginClick:(id)sender {
    [self login];
}

- (void)loginViewShowingLoggedOutUser:(FBLoginView *)loginView {
    
}

- (void)loginViewFetchedUserInfo:(FBLoginView *)loginView
                            user:(id<FBGraphUser>)user {
    //user.id;
    //user.first_name;
}

- (void)loginView:(FBLoginView *)loginView
      handleError:(NSError *)error {
    NSString *alertMessage, *alertTitle;
    if (error.fberrorShouldNotifyUser) {
        // If the SDK has a message for the user, surface it. This conveniently
        // handles cases like password change or iOS6 app slider state.
        alertTitle = @"Facebook Error";
        alertMessage = error.fberrorUserMessage;
    } else if (error.fberrorCategory == FBErrorCategoryAuthenticationReopenSession) {
        // It is important to handle session closures since they can happen
        // outside of the app. You can inspect the error for more context
        // but this sample generically notifies the user.
        alertTitle = @"Session Error";
        alertMessage = @"Your current session is no longer valid. Please log in again.";
    } else if (error.fberrorCategory == FBErrorCategoryUserCancelled) {
        // The user has cancelled a login. You can inspect the error
        // for more context. For this sample, we will simply ignore it.
        NSLog(@"user cancelled login");
    } else {
        // For simplicity, this sample treats other errors blindly.
        alertTitle  = @"Unknown Error";
        alertMessage = @"Error. Please try again later.";
        NSLog(@"Unexpected error:%@", error);
    }
    
    if (alertMessage) {
        [[[UIAlertView alloc] initWithTitle:alertTitle
                                    message:alertMessage
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    }
}

- (IBAction)unwindToMain:(UIStoryboardSegue *)unwindSegue
{

}


@end
