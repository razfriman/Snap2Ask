//
//  AnswerModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface AnswerModel : NSObject

@property (strong, nonatomic) NSNumber *userId;
@property (strong, nonatomic) NSString *answerText;
@property (strong, nonatomic) NSString *status;

@end
