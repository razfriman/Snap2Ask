//
//  CategoryModel.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/29/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "CategoryModel.h"

@implementation CategoryModel

-(id)initWithJSON:(NSDictionary *) JsonData;
{
    self = [super init];
    if(self) {

        self.name = [JsonData objectForKey:@"name"];
        self.value = [[JsonData objectForKey:@"id"] integerValue];

        self.subcategories = [[NSMutableDictionary alloc] init];
        
        NSArray * subcategoriesList = (NSArray *) [JsonData objectForKey:@"subcategories"];
        
        for (int i = 0; i < subcategoriesList.count; i++) {
            
            NSDictionary *subcategoryData = (NSDictionary *) [subcategoriesList objectAtIndex:i];
            
            SubcategoryModel *subcategory = [[SubcategoryModel alloc] initWithJSON:subcategoryData];
        

            [self.subcategories setObject:subcategory forKey:subcategory.name];
        }

    }
    return (self);
}
@end
