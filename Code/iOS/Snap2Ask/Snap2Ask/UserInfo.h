//
//  UserInfo.h
//  Snap2Ask
//
//  Created by Raz Friman on 10/1/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Snap2AskClient.h"
#import "UserModel.h"

@interface UserInfo : NSObject

+ (UserInfo *)sharedClient;

extern NSString *const UserInfoUpdatedNotification;

@property(strong, nonatomic) UserModel *userModel;

@end
