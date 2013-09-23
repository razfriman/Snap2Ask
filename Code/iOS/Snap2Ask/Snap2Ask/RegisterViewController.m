//
//  RegisterViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "RegisterViewController.h"

@interface RegisterViewController ()

@property (weak, nonatomic) IBOutlet UITextField *usernameTextField;
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
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    if(textField == _usernameTextField) {
        [_emailTextField becomeFirstResponder];
    } else if(textField == _emailTextField) {
        [_passwordTextField becomeFirstResponder];
    } else if (textField == _passwordTextField) {
        [_confirmPasswordTextField becomeFirstResponder];
    } else if (textField == _confirmPasswordTextField)
    {
        [_confirmPasswordTextField resignFirstResponder];
        
        // REGISTER ACCOUNT HERE
        // [self registerAccount];
    }
    return YES;
}

- (IBAction)registerAccount:(id)sender {

    if ([_usernameTextField.text isEqualToString:@""]) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please enter a username"
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
                                    message:@"Passwords do not match"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
    } else {
     
        BOOL registerSuccess = NO;
        
        // REGISTER ACCOUNT
        // TODO!!!!
        
        // DEBUG
        registerSuccess = YES;
        
        if (registerSuccess) {
            [self performSegueWithIdentifier:@"registerSuccessSegue" sender:self];
        }
    }
}

@end
