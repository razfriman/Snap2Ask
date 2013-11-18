//
//  Snap2AskClient.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "Snap2AskClient.h"

NSString *const Snap2AskServerBaseString = @"http://54.200.111.247";
NSString *const Snap2AskApiPath = @"/git/snap2ask/Code/web/api/index.php";

NSString *const QuestionsNotification = @"QuestionsNotification";
NSString *const CategoriesNotification = @"CategoriesNotification";
NSString *const LoadUserInfoNotification = @"LoadUserInfoNotification";
NSString *const RegisterUserNotification = @"RegisterUserNotification";
NSString *const LoginUserNotification = @"LoginUserNotification";
NSString *const UploadQuestionImageNotification = @"UploadQuestionImageNotification";
NSString *const NewQuestionSubmittedNotification = @"NewQuestionSubmittedNotification";
NSString *const UserDeletedNotification = @"UserDeletedNotification";
NSString *const BalanceUpdatedNotification = @"BalanceUpdatedNotification";
NSString *const QuestionDeletedNotification = @"QuestionDeletedNotification";
NSString *const AnswerRatedNotification = @"AnswerRatedNotification";

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

// SHOW RESPONSE AS STRING
// NSString *responseString = [[NSString alloc] initWithData:operation.responseData encoding:NSUTF8StringEncoding];
// NSLog(@"%@", responseString);

- (void) loadCategories
{
    
    [_manager GET:[NSString stringWithFormat:@"%@/categories", Snap2AskApiPath] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
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
    
    [_manager GET:[NSString stringWithFormat:@"%@/users/%d", Snap2AskApiPath, userId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
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
     
     
     
     [_manager GET:[NSString stringWithFormat:@"%@/users/%d/questions", Snap2AskApiPath, userId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
         
         NSArray *dataArray = (NSArray*) responseObject;
         
         NSMutableArray *questionArray = [[NSMutableArray alloc] init];
         
         for (int i = 0; i < dataArray.count; i++)
         {
             NSDictionary *data = (NSDictionary*) dataArray[i];
             
             if (![[data objectForKey:@"is_hidden"] boolValue]) {
                 QuestionModel *question = [[QuestionModel alloc] initWithJSON:data];
                 
                 [questionArray addObject:question];
             }
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

     
     [_manager POST:[NSString stringWithFormat:@"%@/login", Snap2AskApiPath] parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {

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
 
     
     [_manager POST:[NSString stringWithFormat:@"%@/users", Snap2AskApiPath] parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
         
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
    
    
    [_manager POST:[NSString stringWithFormat:@"%@/users", Snap2AskApiPath] parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        [[NSNotificationCenter defaultCenter] postNotificationName:LoginUserNotification object:self userInfo:returnedData];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
}

- (void) deleteUser:(int)userId
{
    [_manager DELETE:[NSString stringWithFormat:@"%@/users/%d", Snap2AskApiPath, userId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {

        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        [[NSNotificationCenter defaultCenter] postNotificationName:UserDeletedNotification object:self userInfo:returnedData];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error: %@", error);
    }];
}

- (void) updateSnapCash:(int)amountAdded forUser:(int)userId
{
    
    // Get the current balance
    [_manager GET:[NSString stringWithFormat:@"%@/users/%d", Snap2AskApiPath, userId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSMutableDictionary *initialReturnedData = [((NSDictionary *) responseObject) mutableCopy];
        
        NSInteger oldBalance = [[initialReturnedData objectForKey:@"balance"] integerValue];
        NSInteger newBalance = oldBalance + amountAdded;
        
        [initialReturnedData setValue:@(newBalance) forKey:@"balance"];
        
        // Update the balance
        [_manager PUT:[NSString stringWithFormat:@"%@/users/%d", Snap2AskApiPath, userId] parameters:initialReturnedData success:^(AFHTTPRequestOperation *operation, id responseObject) {

            NSMutableDictionary *returnedData = [((NSDictionary *) responseObject) mutableCopy];
            
            [returnedData setObject:@(amountAdded) forKey:@"amount_added"];
            
            [[NSNotificationCenter defaultCenter] postNotificationName:BalanceUpdatedNotification object:self userInfo:returnedData];
            
        } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
            NSLog(@"Error: %@", error);
            
            NSString *responseString = [[NSString alloc] initWithData:operation.responseData encoding:NSUTF8StringEncoding];
            NSLog(@"%@", responseString);
        }];

    }failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error: %@", error);
        
        // SHOW RESPONSE AS STRING
        NSString *responseString = [[NSString alloc] initWithData:operation.responseData encoding:NSUTF8StringEncoding];
        NSLog(@"%@", responseString);
    }];
}

- (void) deleteQuestion:(int)questionId
{
    [_manager DELETE:[NSString stringWithFormat:@"%@/questions/%d", Snap2AskApiPath, questionId] parameters:nil success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        [[NSNotificationCenter defaultCenter] postNotificationName:QuestionDeletedNotification object:self userInfo:returnedData];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error Deleting Question: %@", error);
        [[NSNotificationCenter defaultCenter] postNotificationName:QuestionDeletedNotification object:self userInfo:nil];
    }];
}

- (void) rateAnswer:(int)answerId withRating:(int)rating
{
    NSDictionary *parameters =  @{@"rating": @(rating)};
    
    
    [_manager PUT:[NSString stringWithFormat:@"%@/answers/%d", Snap2AskApiPath, answerId] parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        [[NSNotificationCenter defaultCenter] postNotificationName:AnswerRatedNotification object:self userInfo:returnedData];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Rate Answer Error: %@", error);
    }];
}

@end
