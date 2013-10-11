//
//  MainViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <GooglePlus/GooglePlus.h>
#import <Parse/Parse.h>
#import <FacebookSDK/FacebookSDK.h>
#import "Snap2AskClient.h"
#import "KeychainItemWrapper.h"
#import "UserInfo.h"

@interface MainViewController : UIViewController<UITextFieldDelegate,FBLoginViewDelegate,GPPSignInDelegate>


- (void)login;

- (IBAction)unwindToMain:(UIStoryboardSegue *)unwindSegue;

@end
