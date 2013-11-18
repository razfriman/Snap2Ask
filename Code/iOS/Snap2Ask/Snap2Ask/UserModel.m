//
//  UserModel.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "UserModel.h"

@implementation UserModel

-(id)initWithJSON:(NSDictionary *) JsonData;
{
    self = [super init];
    if(self) {
        self.userId = [[JsonData objectForKey:@"id"] integerValue];
        self.firstName = [JsonData objectForKey:@"first_name"];
        self.lastName = [JsonData objectForKey:@"last_name"];
        self.email = [JsonData objectForKey:@"email"];
        self.isTutor = [[JsonData objectForKey:@"is_tutor"] boolValue];
        self.authenticationMode = [JsonData objectForKey:@"authentication_mode"];
        self.balance = [[JsonData objectForKey:@"balance"] doubleValue];

        self.averageRating = -1;
        if ([[JsonData objectForKey:@"average_rating"] isKindOfClass:[NSString class]]) {
            self.averageRating = [[JsonData objectForKey:@"average_rating"] integerValue];
        }
        
        self.totalAnswers = [[JsonData objectForKey:@"total_answers"] integerValue];
        self.totalQuestions = [[JsonData objectForKey:@"total_questions"] integerValue];
    }
    return (self);
}
@end
