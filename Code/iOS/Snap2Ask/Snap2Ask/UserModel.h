//
//  UserModel.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface UserModel : NSObject

@property (nonatomic) NSInteger userId;
@property (nonatomic) double balance;
@property (nonatomic) BOOL isTutor;
@property (nonatomic) BOOL preferredCategoryId;
@property (strong, nonatomic) NSString *authenticationMode;
@property (strong, nonatomic) NSString *email;
@property (strong, nonatomic) NSString *firstName;
@property (strong, nonatomic) NSString *lastName;

//is_admin
//date_created

-(id)initWithJSON:(NSDictionary *) JsonData;

@end
