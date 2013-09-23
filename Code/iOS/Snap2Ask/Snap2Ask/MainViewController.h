//
//  MainViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/18/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MainViewController : UIViewController<UITextFieldDelegate>


- (void)login;

- (IBAction)unwindToMain:(UIStoryboardSegue *)unwindSegue;

@end
