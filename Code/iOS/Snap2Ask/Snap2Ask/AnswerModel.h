//
//  AnswerModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface AnswerModel : NSObject

@property (nonatomic) NSInteger answerId;
@property (nonatomic) NSInteger questionId;
@property (nonatomic) NSInteger tutorId;
@property (strong, nonatomic) NSString *text;
@property (strong, nonatomic) NSString *status;

//rating
//date_created

-(id)initWithJSON:(NSDictionary *) JsonData;

@end
