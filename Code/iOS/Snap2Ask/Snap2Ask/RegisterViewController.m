//
//  RegisterViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "RegisterViewController.h"

@interface RegisterViewController ()

@property (weak, nonatomic) IBOutlet UITextField *firstNameTextField;
@property (weak, nonatomic) IBOutlet UITextField *lastNameTextField;
@property (weak, nonatomic) IBOutlet UITextField *emailTextField;
@property (weak, nonatomic) IBOutlet UITextField *passwordTextField;
@property (weak, nonatomic) IBOutlet UITextField *confirmPasswordTextField;

@end

@implementation RegisterViewController

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
    
    // Register for notifications
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(registerUserResponse:) name:RegisterUserNotification object:nil];

}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void) registerUserResponse:(NSNotification *)notification
{
    BOOL registerSuccess = NO;
    
    NSDictionary *response = notification.userInfo;
    
    registerSuccess = [[response objectForKey:@"success"] boolValue];
    
    if (!registerSuccess) {
        
        NSString *reason = [response objectForKey:@"reason"];
        NSLog(@"Register error reason: %@",reason);
        
       [[[UIAlertView alloc] initWithTitle:@"Error" message:@"Could not register account." delegate:self cancelButtonTitle:@"Okay" otherButtonTitles:nil, nil] show];
        return;
    }
    
    if (registerSuccess) {
        
        int userId = [[response objectForKey:@"user_id"] integerValue];
        
        // Save login information to keychain
        KeychainItemWrapper *keychainItem = [[KeychainItemWrapper alloc] initWithIdentifier:@"Snap2Ask" accessGroup:nil];
        [keychainItem setObject:_emailTextField.text forKey:(__bridge NSString*)kSecAttrAccount];
        [keychainItem setObject:_passwordTextField.text forKey:(__bridge NSString*)kSecValueData];
        
        NSString * userChannel = [NSString stringWithFormat:@"user_%d", userId];
        PFInstallation *currentInstallation = [PFInstallation currentInstallation];
        [currentInstallation addUniqueObject:userChannel forKey:@"channels"];
        [currentInstallation saveInBackground];
        
        [self performSegueWithIdentifier:@"registerSuccessSegue" sender:self];
    }
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    if (textField == _firstNameTextField) {
        [_lastNameTextField becomeFirstResponder];
    } else if (textField == _lastNameTextField) {
        [_emailTextField becomeFirstResponder];
    } else if(textField == _emailTextField) {
        [_passwordTextField becomeFirstResponder];
    } else if (textField == _passwordTextField) {
        [_confirmPasswordTextField becomeFirstResponder];
    } else if (textField == _confirmPasswordTextField)
    {
        [_confirmPasswordTextField resignFirstResponder];
        [self registerAccount:self];
    }
    
    return YES;
}

 - (IBAction)tapOnView:(id)sender {
     [_firstNameTextField resignFirstResponder];
     [_lastNameTextField resignFirstResponder];
     [_emailTextField resignFirstResponder];
     [_passwordTextField resignFirstResponder];
     [_confirmPasswordTextField resignFirstResponder];
}

- (IBAction)registerAccount:(id)sender {

    if ([_firstNameTextField.text isEqualToString:@""]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter a first name"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else if ([_lastNameTextField.text isEqualToString:@""]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter a last name"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else if ([_emailTextField.text isEqualToString:@""]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter an email address"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else if ([_passwordTextField.text isEqualToString:@""]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter a password"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else if ([_confirmPasswordTextField.text isEqualToString:@""]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please confirm your password"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else if (![_passwordTextField.text isEqualToString:_confirmPasswordTextField.text]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Password us do not match"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else if (![self emailValidation:_emailTextField.text]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Invalid email address"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else {
        
        // Register using the REST API
        [[Snap2AskClient sharedClient] registerUser:[_emailTextField.text lowercaseString] withFirstName:_firstNameTextField.text withLastName:_lastNameTextField.text withPassword:_passwordTextField.text withAuthenticationMode:@"custom"];
    }
}
               
- (BOOL) emailValidation: (NSString *) emailToValidate {
    NSString *regexForEmailAddress = @"[A-Z0-9a-z._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}";
    NSPredicate *emailValidation = [NSPredicate predicateWithFormat:@"SELF MATCHES %@",regexForEmailAddress];
    return [emailValidation evaluateWithObject:emailToValidate];
}

@end
