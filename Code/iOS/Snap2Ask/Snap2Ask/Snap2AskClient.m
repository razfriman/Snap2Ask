//
//  Snap2AskClient.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "Snap2AskClient.h"

NSString *const Snap2AskServerBaseString = @"http://54.200.111.247";

NSString *const QuestionsNotification = @"QuestionsNotification";
NSString *const CategoriesNotification = @"CategoriesNotification";
NSString *const LoadUserInfoNotification = @"LoadUserInfoNotification";
NSString *const RegisterUserNotification = @"RegisterUserNotification";
NSString *const LoginUserNotification = @"LoginUserNotification";
NSString *const UploadQuestionImageNotification = @"UploadQuestionImageNotification";

@implementation Snap2AskClient

+ (Snap2AskClient *)sharedClient {
    
    static Snap2AskClient *_sharedClient = nil;
    static dispatch_once_t onceToken;
    
    dispatch_once(&onceToken, ^{
        _sharedClient = [[Snap2AskClient alloc] init];
        
    });
    
    return _sharedClient;
}

-(id)init
{
    self = [super init];
    if (!self) {
        return nil;
    }
    
    NSURL *baseURL = [NSURL URLWithString:Snap2AskServerBaseString];
    self.manager = [[AFHTTPRequestOperationManager alloc] initWithBaseURL:baseURL];
    self.manager.responseSerializer = [AFJSONResponseSerializer serializer];
    self.manager.requestSerializer = [AFJSONRequestSerializer serializer];
    
    [[AFNetworkActivityIndicatorManager sharedManager] setEnabled:YES];
    
    return self;
    
}

- (void) loadCategories
{
    
    
    [_manager GET:@"/snap2ask/api/index.php/categories" parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSArray *dataArray = (NSArray*) responseObject;
        
        NSMutableDictionary *categoryDict = [[NSMutableDictionary alloc] init];
        
        
        for (int i = 0; i < dataArray.count; i++)
        {
            NSDictionary *categoryData = (NSDictionary *) [dataArray objectAtIndex:i];
            
            CategoryModel *category = [[CategoryModel alloc] initWithJSON:categoryData];
            
            [categoryDict setObject:category forKey:category.name];
        }
        
        
        [[NSNotificationCenter defaultCenter] postNotificationName:CategoriesNotification object:self userInfo:categoryDict];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
}

- (void) loadUserInfo:(int)userId
{
    
    [_manager GET:[NSString stringWithFormat:@"/snap2ask/api/index.php/users/%d", userId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *dataDict = (NSDictionary *) responseObject;
        
        NSMutableDictionary *userDict = [[NSMutableDictionary alloc] init];
        
        UserModel *user = [[UserModel alloc] initWithJSON:dataDict];
        
        [userDict setObject:user forKey:@"user"];
        
        [[NSNotificationCenter defaultCenter] postNotificationName:LoadUserInfoNotification object:self userInfo:userDict];
        
        
    }failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
    
}

- (void) loadQuestionsForUser:(int)userId
{
     
     
     
     [_manager GET:[NSString stringWithFormat:@"/snap2ask/api/index.php/users/%d/questions", userId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
         
         NSArray *dataArray = (NSArray*) responseObject;
         
         NSMutableArray *questionArray = [[NSMutableArray alloc] init];
         
         for (int i = 0; i < dataArray.count; i++)
         {
             NSDictionary *data = (NSDictionary*) dataArray[i];
             
             QuestionModel *question = [[QuestionModel alloc] initWithJSON:data];
             
             [questionArray addObject:question];
         }
         
         NSMutableDictionary *returnDict = [[NSMutableDictionary alloc] init];
         [returnDict setObject:questionArray forKey:@"questions"];
         
         [[NSNotificationCenter defaultCenter] postNotificationName:QuestionsNotification object:self userInfo:returnDict];
         
         
     }failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         NSLog(@"Error: %@", error);
     }];
 }

- (void) login:(NSString *)accountIdentifier withPassword:(NSString *)password withAuthenticationMode:(NSString *)authenticationMode
{
     
     NSDictionary *parameters =  @{
                                   @"account_identifier": accountIdentifier,
                                   @"password": password,
                                   @"authentication_mode": authenticationMode
                                   };

     
     [_manager POST:@"/snap2ask/api/index.php/login" parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {

         NSDictionary *returnedData = (NSDictionary *) responseObject;
         
         [[NSNotificationCenter defaultCenter] postNotificationName:LoginUserNotification object:self userInfo:returnedData];
         
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         NSLog(@"Error: %@", error);
     }];
 }

- (void) registerUser:(NSString *)email withFirstName:(NSString *)firstName withLastName:(NSString *)lastName withPassword:(NSString *)password withAuthenticationMode:(NSString *)authenticationMode
{
     
     NSDictionary *parameters =  @{
                                   @"first_name": firstName,
                                   @"last_name": lastName,
                                   @"email": email,
                                   @"password": password,
                                   @"is_tutor": @false,
                                   @"authentication_mode": authenticationMode,
                                   @"register_or_login":@false
                                   };
 
     
     [_manager POST:@"/snap2ask/api/index.php/users" parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
         
         NSDictionary *returnedData = (NSDictionary *) responseObject;
         
         [[NSNotificationCenter defaultCenter] postNotificationName:RegisterUserNotification object:self userInfo:returnedData];
         
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         NSLog(@"Error: %@", error);
     }];
 }

- (void) loginOrRegister:(NSString *)email withOauthId:(NSString *)oauthId withPassword:(NSString *)password withFirstName:(NSString *)firstName withLastName:(NSString *)lastName withAuthenticationMode:(NSString *)authenticationMode;
{
    NSDictionary *parameters =  @{
                                  @"first_name": firstName,
                                  @"last_name": lastName,
                                  @"email": email,
                                  @"oauth_id": oauthId,
                                  @"password": password,
                                  @"is_tutor": @false,
                                  @"authentication_mode": authenticationMode,
                                  @"register_or_login":@true
                                  };
    
    
    [_manager POST:@"/snap2ask/api/index.php/users" parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        [[NSNotificationCenter defaultCenter] postNotificationName:LoginUserNotification object:self userInfo:returnedData];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
}

@end
