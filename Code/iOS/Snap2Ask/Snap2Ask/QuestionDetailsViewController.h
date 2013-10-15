//
//  QuestionDetailsViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SDWebImage/UIImageView+WebCache.h"
#import "QuestionModel.h"
#import "AnswerModel.h"
#import "ImageDetailsViewController.h"

@interface QuestionDetailsViewController : UITableViewController

@property (strong, nonatomic) QuestionModel *question;

@end
