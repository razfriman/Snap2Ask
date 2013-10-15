//
//  Snap2AskClient.h
//  Snap2Ask
//
//  Created by Raz Friman on 9/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "AFHTTPRequestOperationManager.h"
#import "AFNetworkActivityIndicatorManager.h"
#import "AFURLRequestSerialization.h"
#import "AFURLResponseSerialization.h"
#import "CategoryModel.h"
#import "QuestionModel.h"
#import "UserModel.h"

@interface Snap2AskClient : NSObject

+ (Snap2AskClient *)sharedClient;

extern NSString *const Snap2AskApiPath;
extern NSString *const QuestionsNotification;
extern NSString *const CategoriesNotification;
extern NSString *const LoadUserInfoNotification;
extern NSString *const RegisterUserNotification;
extern NSString *const LoginUserNotification;
extern NSString *const UploadQuestionImageNotification;
extern NSString *const NewQuestionSubmittedNotification;

@property (strong, nonatomic) AFHTTPRequestOperationManager *manager;

- (void) loadQuestionsForUser:(int)userId;
- (void) loadCategories;
- (void) loadUserInfo:(int)userId;

- (void) login:(NSString *)accountIdentifier withPassword:(NSString *)password withAuthenticationMode:(NSString *)authenticationMode;

- (void) loginOrRegister:(NSString *)email withOauthId:(NSString *)oauthId withPassword:(NSString *)password withFirstName:(NSString *)firstName withLastName:(NSString *)lastName withAuthenticationMode:(NSString *)authenticationMode;

- (void) registerUser:(NSString *)email withFirstName:(NSString *)firstName withLastName:(NSString *)lastName withPassword:(NSString *)password withAuthenticationMode:(NSString *)authenticationMode;



@end
