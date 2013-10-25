//
//  QuestionModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/24/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "AnswerModel.h"
#import "CategoryModel.h"
#import "SubcategoryModel.h"

@interface QuestionModel : NSObject

@property (nonatomic) NSInteger questionId;
@property (nonatomic) NSInteger studentId;
@property (nonatomic) NSInteger timesAnswered;
@property (nonatomic) NSInteger status;
@property (strong, nonatomic) NSString *category;
@property (strong, nonatomic) NSString *subcategory;
@property (strong, nonatomic) NSString *description;
@property (strong, nonatomic) NSString *imageThumbnailUrl;
@property (strong, nonatomic) NSString *imageUrl;
@property (strong, nonatomic) NSArray *answers;

//date_created

-(id)initWithJSON:(NSDictionary *) JsonData;
@end
