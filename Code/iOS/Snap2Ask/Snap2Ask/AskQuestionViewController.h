//
//  AskQuestionViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/26/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "MBProgressHUD.h"
#import "Snap2AskClient.h"
#import "UserInfo.h"


@interface AskQuestionViewController : UITableViewController<UINavigationControllerDelegate, UIActionSheetDelegate,UIImagePickerControllerDelegate,UITextFieldDelegate>

@property (strong, nonatomic) UIImagePickerController* mediaPicker;
@property (strong, nonatomic) NSDictionary* categoryData;

- (IBAction)unwindToAskQuestion:(UIStoryboardSegue *)unwindSegue;

- (void) updateSelectedCategory:(CategoryModel *)category withSubcategory:(SubcategoryModel *)subcategory;

- (void) uploadQuestionForUser:(NSInteger)userId withCategory:(NSInteger)categoryId withSubcategory:(NSInteger)subcategoryId withDescription:(NSString *)description withImage:(NSData *)imageData;

- (void) uploadQuestionImage:(NSInteger)questionId withImage:(NSData *)imageData;

@end
