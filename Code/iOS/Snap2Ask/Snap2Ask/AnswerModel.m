//
//  AnswerModel.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "AnswerModel.h"

@implementation AnswerModel

-(id)initWithJSON:(NSDictionary *) JsonData;
{
    self = [super init];
    if(self) {
        
        
        self.tutorId = [[JsonData objectForKey:@"tutor_id"] integerValue];
        self.text = [JsonData objectForKey:@"text"];
        self.status = [JsonData objectForKey:@"status"];
    }
    return (self);
}


@end
