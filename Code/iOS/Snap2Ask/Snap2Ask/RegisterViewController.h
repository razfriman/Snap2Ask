//
//  Register2ViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 12/5/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <Parse/Parse.h>
#import "KeychainItemWrapper.h"
#import "Snap2AskClient.h"
#import "UserInfo.h"

@interface RegisterViewController : UITableViewController<UITextFieldDelegate>


- (BOOL) emailValidation: (NSString *) emailToValidate;
@end
