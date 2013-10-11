//
//  RegisterViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <Parse/Parse.h>
#import "KeychainItemWrapper.h"
#import "Snap2AskClient.h"

@interface RegisterViewController : UIViewController<UITextFieldDelegate>


- (BOOL) emailValidation: (NSString *) emailToValidate;
@end
