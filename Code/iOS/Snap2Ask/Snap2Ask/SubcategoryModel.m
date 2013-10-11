//
//  SubcategoryModel.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/30/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "SubcategoryModel.h"

@implementation SubcategoryModel

-(id)initWithJSON:(NSDictionary *) JsonData;
{
    self = [super init];
    if(self) {
        
        self.name = [JsonData objectForKey:@"name"];
        self.subcategoryId = [[JsonData objectForKey:@"id"] integerValue];
        self.categoryId = [[JsonData objectForKey:@"category_id"] integerValue];
        
    }
    return (self);
}


@end
