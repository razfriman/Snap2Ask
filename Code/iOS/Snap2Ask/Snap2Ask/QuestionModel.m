//
//  QuestionModel.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/24/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "QuestionModel.h"

@implementation QuestionModel

-(id)initWithJSON:(NSDictionary *) JsonData;
{
    self = [super init];
    if(self) {
        
        self.questionId = [[JsonData objectForKey:@"id"] integerValue];
        self.status = [[JsonData objectForKey:@"status"] integerValue];
        self.category = [JsonData objectForKey:@"category"];
        self.subcategory = [JsonData objectForKey:@"subcategory"];
        self.description = [JsonData objectForKey:@"description"];
        self.imageUrl = [JsonData objectForKey:@"image_url"];
        self.imageThumbnailUrl = [JsonData objectForKey:@"image_thumbnail_url"];
        
        NSArray *answerArray = [JsonData objectForKey:@"answers"];
        
        if (answerArray.count > 0) {
            self.answer = [[AnswerModel alloc] initWithJSON:[answerArray objectAtIndex:0]];
        }

    }
    return (self);
}


@end
