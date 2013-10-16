//
//  MainViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "MainViewController.h"
#import <GoogleOpenSource/GoogleOpenSource.h>
#import <GooglePlus/GooglePlus.h>
#import "MBProgressHUD.h"

static NSString * const kGoogleClientId = @"324181753300-ffefeh38gmnb5hisj8ubjnmppmb3ea0v.apps.googleusercontent.com";

@interface MainViewController () <FBLoginViewDelegate>

@property (weak, nonatomic) IBOutlet UITextField *emailTextField;
@property (weak, nonatomic) IBOutlet UITextField *passwordTextField;
@property (weak, nonatomic) IBOutlet UIButton *customSignInButton;
@property (weak, nonatomic) IBOutlet FBLoginView *facebookSignInButton;
@property (weak, nonatomic) IBOutlet GPPSignInButton *googleSignInButton;
@property (weak, nonatomic) IBOutlet UIButton *registerButton;

@property (strong, nonatomic) MBProgressHUD *hud;

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
    
    // Init the UserInfo class
    [UserInfo sharedClient];
    
    // Register for notifications
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(userLoginUpdated:) name:LoginUserNotification object:nil];
    
    // Init the Google+ Login object
    GPPSignIn *signIn = [GPPSignIn sharedInstance];
    signIn.clientID = kGoogleClientId;
    signIn.scopes = [NSArray arrayWithObjects:kGTLAuthScopePlusLogin,nil];
    signIn.delegate = self;
    
    // Request email from Google+
    signIn.shouldFetchGoogleUserEmail = YES;
    signIn.shouldFetchGoogleUserID = YES;
    
    // Attempt to auto-login using google
    [[GPPSignIn sharedInstance] trySilentAuthentication];
    
    // Set the facebook read permissions to ask for email
    _facebookSignInButton.readPermissions =  @[@"email"];
    
    [signIn disconnect];
    
}

- (void) viewDidAppear:(BOOL)animated {
    
    // Load the previous authentication mode from the keychain
    KeychainItemWrapper *keychainAuthenticationMode = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask_AuthenticationMode" accessGroup:nil];
    NSString *authenticationMode = [keychainAuthenticationMode objectForKey:(__bridge NSString*)kSecAttrAccount];
    
    if (![authenticationMode isEqualToString:@""]) {
        
        //[self showLoginIndicator];
    }
    
    // Sign in automatically
    if ([authenticationMode isEqualToString:@"custom"]) {
        
        // Load the saved username/password from the keychain
        KeychainItemWrapper *keychainItem = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask" accessGroup:nil];
        NSString *email = [keychainItem objectForKey:(__bridge NSString*)kSecAttrAccount];
        NSString *password = [keychainItem objectForKey:(__bridge NSString*)kSecValueData];
        
        if (email && password && email.length > 0 && password.length > 0) {
            // Attempt to validate the user automatically
            _emailTextField.text = email;
            _passwordTextField.text = password;
            [self login];
        }
    }
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void) userLoginUpdated:(NSNotification *)notification
{
    
    [self hideLoginIndicator];
    
    NSDictionary *response = notification.userInfo;

    BOOL success = [[response objectForKey:@"success"] boolValue];
    
    BOOL isRegisterOrLogin = [[response objectForKey:@"register_or_login"] boolValue];
    
    if (!isRegisterOrLogin &&!success) {
     
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Incorrect email/password"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
        return;
    } else if (!success) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Cannot create account"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
        return;
    }
    
    int userId = [[response objectForKey:@"user_id"] integerValue];
    NSString *authenticationMode = [response objectForKey:@"authentication_mode"];

    // Subscribe to the user channel for push notifications
    NSString * userChannel = [NSString stringWithFormat:@"user_%d", userId];
    PFInstallation *currentInstallation = [PFInstallation currentInstallation];
    [currentInstallation addUniqueObject:userChannel forKey:@"channels"];
    [currentInstallation saveInBackground];

    // Save authentication mode to keychain
    KeychainItemWrapper *keychainAuthenticationMode = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask_AuthenticationMode" accessGroup:nil];
    [keychainAuthenticationMode setObject:authenticationMode forKey:(__bridge NSString*)kSecAttrAccount];
    
    if ([authenticationMode isEqualToString:@"custom"]) {
        // Save login information to keychain
        KeychainItemWrapper *keychainItem = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask" accessGroup:nil];
        [keychainItem setObject:_emailTextField.text forKey:(__bridge NSString*)kSecAttrAccount];
        [keychainItem setObject:_passwordTextField.text forKey:(__bridge NSString*)kSecValueData];
    }
    
    // Init the UserInfo shared class and assign the user's id
    [UserInfo sharedClient].userModel = [[UserModel alloc] init];
    [UserInfo sharedClient].userModel.userId = userId;
    
    // Load the user information
    [[Snap2AskClient sharedClient] loadUserInfo:userId];
    
    // Reset password field
    _passwordTextField.text = @"";
    
    // Continue past the login screen
    [self performSegueWithIdentifier:@"loginSegue" sender:self];
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    if(textField == _emailTextField) {
        [_passwordTextField becomeFirstResponder];
    } else if (textField == _passwordTextField) {
        [_passwordTextField resignFirstResponder];
        [self login];
    }
    
    return YES;
}

- (IBAction)tapOnView:(id)sender {
    [_emailTextField resignFirstResponder];
    [_passwordTextField resignFirstResponder];
}

- (void)login {
    
    if ([_emailTextField.text isEqualToString:@""]) {
        // Please enter an email
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter an email"
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

        [self showLoginIndicator];
        
        [[Snap2AskClient sharedClient] login:[_emailTextField.text lowercaseString] withPassword:_passwordTextField.text withAuthenticationMode:@"custom"];
    }
}

- (IBAction)loginClick:(id)sender {
    [self login];
}


#pragma mark - Google
- (void)finishedWithAuth: (GTMOAuth2Authentication *)auth error: (NSError *) error
{
    if (error) {
        NSLog(@"Google+ Auth Error %@ ",error);
    } else {
        
        [self showLoginIndicator];
        
        
        NSString *token = auth.accessToken;
        NSString *oauthId = [auth.properties objectForKey:@"user_id"];
        
        // Load the previous authentication mode from the keychain
        KeychainItemWrapper *keychainAuthenticationMode = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask_AuthenticationMode" accessGroup:nil];
        NSString *authenticationMode = [keychainAuthenticationMode objectForKey:(__bridge NSString*)kSecAttrAccount];
        
        
        // Sign in
        if([authenticationMode isEqualToString:@"google"]) {
            // Login with google
            [[Snap2AskClient sharedClient] login:oauthId withPassword:token withAuthenticationMode:@"google"];
        } else {
            // Register with google
            
            GTLServicePlus* plusService = [[GTLServicePlus alloc] init];
            plusService.retryEnabled = YES;
            [plusService setAuthorizer:auth];
            GTLQueryPlus *query = [GTLQueryPlus queryForPeopleGetWithUserId:@"me"];
            
            [plusService executeQuery:query
                    completionHandler:^(GTLServiceTicket *ticket,
                                        GTLPlusPerson *person,
                                        NSError *error) {
                        if (error) {
                            NSLog(@"Google+ Query Error %@ ",error);
                        } else {
                            
                            NSString *email = auth.userEmail;
                            NSString *firstName = person.name.givenName;
                            NSString *lastName = person.name.familyName;
                            NSString *token = auth.accessToken;
                            NSString *oauthId = [auth.properties objectForKey:@"user_id"];
                            
                            // LOG IN USING GOOGLE / REGISTER NEW ACCOUNT USING GOOGLE
                            [[Snap2AskClient sharedClient] loginOrRegister:email withOauthId:oauthId withPassword:token  withFirstName:firstName withLastName:lastName withAuthenticationMode:@"google"];
                        }
                    }];
        }
    }
}

- (void)didDisconnectWithError:(NSError *)error {
    if (error) {
        NSLog(@"Received error %@", error);
    } else {
        // The user is signed out and disconnected.
        // Clean up user data as specified by the Google+ terms.
    }
}

#pragma mark - Facebook
- (void)loginViewFetchedUserInfo:(FBLoginView *)loginView
                            user:(id<FBGraphUser>)user {
    
    [self showLoginIndicator];

    NSString *email = [user objectForKey:@"email"];
    NSString *oauthId = user.id;
    NSString *firstName = user.first_name;
    NSString *lastName = user.last_name;
    NSString *token = [[[FBSession activeSession] accessTokenData] accessToken];
    
    // Load the previous authentication mode from the keychain
    KeychainItemWrapper *keychainAuthenticationMode = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask_AuthenticationMode" accessGroup:nil];
    NSString *authenticationMode = [keychainAuthenticationMode objectForKey:(__bridge NSString*)kSecAttrAccount];
    
    
    // Sign in
    if([authenticationMode isEqualToString:@"facebook"]) {
        // Login with facebook
        [[Snap2AskClient sharedClient] login:oauthId withPassword:token withAuthenticationMode:@"facebook"];
    } else {
        // Register with facebook
        [[Snap2AskClient sharedClient] loginOrRegister:email withOauthId:oauthId withPassword:token withFirstName:firstName withLastName:lastName withAuthenticationMode:@"facebook"];
    }
    

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
        
        [self hideLoginIndicator];
        
        [[[UIAlertView alloc] initWithTitle:alertTitle
                                    message:alertMessage
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    }
}

- (void)hideLoginIndicator
{
    if (_hud) {
        // Hide the indicator
        [_hud hide:YES];
        _hud = nil;
    }
}

- (void)showLoginIndicator
{
    if (!_hud) {
        _hud = [MBProgressHUD showHUDAddedTo:self.view animated:YES];
        _hud.mode = MBProgressHUDModeIndeterminate;
        _hud.labelText = @"Logging In";
        _hud.removeFromSuperViewOnHide = YES;
    }
}
- (IBAction)unwindToMain:(UIStoryboardSegue *)unwindSegue
{
    
}


@end
