//
//  UserModel.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "UserModel.h"

NSInteger const AuthenticationModeCustomLogin = 1;
NSInteger const AuthenticationModeFacebookLogin = 2;
NSInteger const AuthenticationModeGoogleLogin = 3;

@implementation UserModel

-(id)initWithJSON:(NSDictionary *) JsonData;
{
    self = [super init];
    if(self) {
        
        self.userId = [[JsonData objectForKey:@"id"] integerValue];
        self.firstName = [JsonData objectForKey:@"first_name"];
        self.lastName = [JsonData objectForKey:@"last_name"];
        self.email = [JsonData objectForKey:@"email"];
        self.isTutor = [[JsonData objectForKey:@"is_tutor"] boolValue];
        self.authenticationMode = [JsonData objectForKey:@"authentication_mode"];
    }
    return (self);
}
@end
