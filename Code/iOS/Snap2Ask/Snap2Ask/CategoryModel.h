//
//  CategoryModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/29/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "SubcategoryModel.h"

@interface CategoryModel : NSObject

@property (nonatomic) NSInteger categoryId;
@property (strong, nonatomic) NSString *name;
@property (nonatomic) NSInteger value;

@property (strong, nonatomic) NSMutableDictionary *subcategories;

-(id)initWithJSON:(NSDictionary *) JsonData;

@end
