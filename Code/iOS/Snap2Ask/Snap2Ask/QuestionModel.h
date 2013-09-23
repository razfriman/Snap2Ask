//
//  QuestionModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/24/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface QuestionModel : NSObject

@property BOOL isAnswered;
@property (strong, nonatomic) NSString *category;
@property (strong, nonatomic) NSString *subcategory;
@property (strong, nonatomic) NSString *description;
@property (strong, nonatomic) NSString *imageThumbnailUrl;
@property (strong, nonatomic) NSString *imageUrl;
@end
