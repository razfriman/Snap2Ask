//
//  Snap2AskClient.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "AFHTTPClient.h"
#import "QuestionModel.h"

@interface Snap2AskClient : AFHTTPClient

+ (Snap2AskClient *)sharedClient;

//@property (nonatomic, strong) QuestionsModel* questionsModel;
extern NSString *const QuestionsNotification;

- (void) getQuestionsUsingArray:(NSMutableArray *)array ForUser:(int)userId;


@end
