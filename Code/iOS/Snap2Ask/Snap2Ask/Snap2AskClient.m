//
//  Snap2AskClient.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "Snap2AskClient.h"
#import "AFJSONRequestOperation.h"
#import "AFHTTPRequestOperation.h"
#import "AFNetworkActivityIndicatorManager.h"
#import "Utilities.h"


//NSString *const Snap2AskServerBaseString = @"http://local.snap2ask.com/";
NSString *const Snap2AskServerBaseString = @"http://razfriman.apiary.io/";

NSString *const QuestionsNotification = @"QuestionsNotification";

@implementation Snap2AskClient

+ (Snap2AskClient *)sharedClient {
    
    static Snap2AskClient *_sharedClient = nil;
    static dispatch_once_t onceToken;
    
    dispatch_once(&onceToken, ^{
        _sharedClient = [[Snap2AskClient alloc] initWithBaseURL:[NSURL URLWithString:Snap2AskServerBaseString]];
    });
    
    return _sharedClient;
}

- (void) getQuestionsUsingArray:(NSMutableArray *)array ForUser:(int)userId {
    [[AFNetworkActivityIndicatorManager sharedManager] setEnabled:YES];
    [[AFNetworkActivityIndicatorManager sharedManager] incrementActivityCount];
    
    NSMutableURLRequest *request = [self requestWithMethod:@"GET" path:[NSString stringWithFormat:@"/users/%d/questions", userId]  parameters:nil];
    
    AFJSONRequestOperation *operation = [AFJSONRequestOperation JSONRequestOperationWithRequest:request
                                                                                        success:^(NSURLRequest *request, NSHTTPURLResponse *response, id JSON) {
                                                                                            
                                                                                            [[AFNetworkActivityIndicatorManager sharedManager] decrementActivityCount];
                                                                                            NSArray *dataArray = (NSArray*) JSON;
                                                                                            
                                                                                            
                                                                                            for (int i = 0; i < dataArray.count; i++)
                                                                                            {
                                                                                                NSDictionary *data = (NSDictionary*) dataArray[i];
                                                                                                [array addObject:0];
                                                                                            }

                                                                                            [[NSNotificationCenter defaultCenter] postNotificationName:QuestionsNotification object:self];
                                                                                            
                                                                                            
                                                                                        }failure:^(NSURLRequest *request, NSHTTPURLResponse *response, NSError *error, id JSON) {
                                                                                            [[AFNetworkActivityIndicatorManager sharedManager] decrementActivityCount];
                                                                                            
                                                                                            NSLog(@"Error: %@", error);
                                                                                        }];
    
    [operation start];
}

@end
