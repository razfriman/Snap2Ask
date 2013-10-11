//
//  QuestionTableViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/30/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "QuestionDetailsViewController.h"
#import "QuestionCell.h"
#import "SDWebImage/UIImageView+WebCache.h"
#import "QuestionModel.h"
#import "Snap2AskClient.h"
#import "UserInfo.h"

@interface QuestionTableViewController : UITableViewController<UITableViewDataSource,UITableViewDelegate>

@property(nonatomic, strong) NSMutableArray *questions;

-(NSArray *) getFilteredQuestions;

@end
