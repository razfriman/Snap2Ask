//
//  UserInfo.m
//  Snap2Ask
//
//  Created by Raz Friman on 10/1/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "UserInfo.h"

NSString *const UserInfoUpdatedNotification = @"UserInfoUpdatedNotification";


@implementation UserInfo

+ (UserInfo *)sharedClient
{
    
    static UserInfo *_sharedClient = nil;
    static dispatch_once_t onceToken;
    
    dispatch_once(&onceToken, ^{
        _sharedClient = [[UserInfo alloc] init];
    });
    
    return _sharedClient;
}

-(id) init
{
    self = [super init];
    if (self) {
        // Initialization code
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(userInfoUpdated:) name:LoadUserInfoNotification object:nil];

    }
    return self;
}

- (void) userInfoUpdated:(NSNotification *)notification
{
    UserModel *userInfo = [notification.userInfo objectForKey:@"user"];
    self.userModel = userInfo;
    
    [[NSNotificationCenter defaultCenter] postNotificationName:UserInfoUpdatedNotification object:self userInfo:nil];
    
}


@end
