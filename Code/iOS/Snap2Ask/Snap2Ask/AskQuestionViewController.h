//
//  AskQuestionViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/26/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AskQuestionViewController : UITableViewController<UINavigationControllerDelegate, UIActionSheetDelegate,UIImagePickerControllerDelegate,UITextFieldDelegate>

@property (strong, nonatomic) UIImagePickerController* mediaPicker;

- (void) finishSubmitQuestion;

@end
