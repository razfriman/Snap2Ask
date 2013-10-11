//
//  SubcategoryModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/30/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface SubcategoryModel : NSObject

@property (nonatomic) NSInteger subcategoryId;
@property (nonatomic) NSInteger categoryId;
@property (strong, nonatomic) NSString *name;

-(id)initWithJSON:(NSDictionary *) JsonData;


@end
