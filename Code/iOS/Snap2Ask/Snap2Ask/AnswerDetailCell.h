//
//  AnswerDetailCell.h
//  Snap2Ask
//
//  Created by Raz Friman on 10/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AnswerDetailCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *tutorLabel;
@property (weak, nonatomic) IBOutlet UILabel *tutorRankLabel;
@property (weak, nonatomic) IBOutlet UILabel *answerLabel;

@end
