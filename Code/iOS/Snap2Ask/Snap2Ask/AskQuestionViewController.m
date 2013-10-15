//
//  AskQuestionViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/26/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "AskQuestionViewController.h"
#import "UIProgressView+AFNetworking.h"
#import "QuestionTableViewController.h"
#import "SelectCategoryViewController.h"

@interface AskQuestionViewController ()

@property (weak, nonatomic) IBOutlet UIImageView *imagePreview;
@property (weak, nonatomic) IBOutlet UITextField *descriptionText;
@property (weak, nonatomic) IBOutlet UILabel *categoryLabel;
@property (weak, nonatomic) IBOutlet UIButton *askButton;

@property (strong, nonatomic) CategoryModel *selectedCategory;
@property (strong, nonatomic) SubcategoryModel *selectedSubcategory;

@property (nonatomic) BOOL hasSelectedImage;

@property (weak, nonatomic) MBProgressHUD *hud;

@end

@implementation AskQuestionViewController

- (id)initWithStyle:(UITableViewStyle)style
{
    self = [super initWithStyle:style];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];

    _hasSelectedImage = NO;
    
    _mediaPicker = [[UIImagePickerController alloc] init];
    _mediaPicker.delegate = self;
    _mediaPicker.allowsEditing = YES;
        
    // Init the category data dictionary
    self.categoryData = [[NSDictionary alloc] init];
    
    // Register for notifications
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(categoriesUpdated:) name:CategoriesNotification object:nil];

    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(questionUploaded:) name:    UploadQuestionImageNotification object:nil];

    
    // Load categories from the REST API
    [[Snap2AskClient sharedClient] loadCategories];
    
    // Preserve selection between presentations.
    self.clearsSelectionOnViewWillAppear = NO;
}

- (void) categoriesUpdated:(NSNotification *)notification
{
    // Update the data
    self.categoryData = notification.userInfo;
}

// After a question has been uploaded
- (void) questionUploaded:(NSNotification *)notification
{
    NSDictionary *responseDict = notification.userInfo;
    
    
    if (_hud) {
        // Hide the progress HUD
        [_hud hide:YES];
    }
    
    if (![[responseDict objectForKey:@"success"] boolValue]) {
        
        UIAlertView *alertView = [[UIAlertView alloc]
                                  initWithTitle:@"Error"
                                  message:@"Something went wrong while uploading your question."
                                  delegate:nil
                                  cancelButtonTitle:@"OK"
                                  otherButtonTitles:nil];
        [alertView show];
        return;
    }
    
    UIAlertView *alertView = [[UIAlertView alloc]
                              initWithTitle:@"Success"
                              message:@"Your question has been posted."
                              delegate:nil
                              cancelButtonTitle:@"OK"
                              otherButtonTitles:nil];
    [alertView show];
    
    // Add the image to the "My Questions" tab
    NSDictionary *questionData = @{@"id": @([[responseDict objectForKey:@"question_id"] integerValue]),
                                   @"status": @0,
                                   @"category": _selectedCategory.name,
                                   @"subcategory": _selectedSubcategory.name,
                                   @"description": _descriptionText.text,
                                   @"image_url": [responseDict objectForKey:@"image_url"],
                                   @"image_thumbnail_url": [responseDict objectForKey:@"image_thumbnail_url"]
                                   };
    [[NSNotificationCenter defaultCenter] postNotificationName:NewQuestionSubmittedNotification object:self userInfo:questionData];
    
    
    // Move to the "My Questions" tab
    [self.tabBarController setSelectedIndex:0];
    
    // Clear the current data
    _imagePreview.image = [UIImage imageNamed:@"Placeholder"];
    _descriptionText.text = @"";
    _categoryLabel.text = @"Select a category";
    _hasSelectedImage = NO;
    _selectedCategory = nil;
    _selectedSubcategory = nil;
    

}


- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return 4;
}

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row == 2) {
        // Select Category

        // TODO: Show PUSH segue to select category
        
        [tableView deselectRowAtIndexPath:indexPath animated:YES];
    }
}

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    if(textField == _descriptionText) {
        [_descriptionText resignFirstResponder];
    }
    
    return YES;
}


- (IBAction)tapImageView:(id)sender {
    [self selectPhoto:sender];
}

- (IBAction)selectPhoto:(id)sender {
    
    [_descriptionText resignFirstResponder];
    
    if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
        
        UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:nil delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Take Photo", @"Choose Existing", nil];
        
        [actionSheet showInView:self.view];
    }
    else {
        _mediaPicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
        [self presentViewController:_mediaPicker animated:YES completion:NULL];
    }
    
}

#pragma mark - Action Sheet
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex {
    if (buttonIndex == 0) {
        _mediaPicker.sourceType = UIImagePickerControllerSourceTypeCamera;
    } else if (buttonIndex == 1) {
        _mediaPicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
    } else if (buttonIndex == 2) {
        // Cancel button
        return;
    }
    
    [self presentViewController:_mediaPicker animated:YES completion:NULL];
}

#pragma mark Image Picker Controller
- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
    UIImage *img = [info objectForKey:UIImagePickerControllerEditedImage];
    if (!img) img = [info objectForKey:UIImagePickerControllerOriginalImage];
    
    _imagePreview.image = img;
    
    _hasSelectedImage = YES;
    
    [self dismissViewControllerAnimated:YES completion:^{ }];
}

- (void)imagePickerControllerDidCancel:(UIImagePickerController *)picker
{
    [self dismissViewControllerAnimated:YES completion:^{ }];
}

- (void) updateSelectedCategory:(CategoryModel *)category withSubcategory:(SubcategoryModel *)subcategory
{
    // Set the selected categories
    _selectedCategory = category;
    _selectedSubcategory = subcategory;
    
    // Update the label
    self.categoryLabel.text = [NSString stringWithFormat:@"%@ - %@", category.name, subcategory.name];
}

- (IBAction)submitQuestion:(id)sender {
    

    if (!_hasSelectedImage) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please select an image"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
        return;
    }
    
    if (_selectedCategory == nil || _selectedSubcategory == nil) {
        [[[UIAlertView alloc] initWithTitle:@"Error"
                                    message:@"Please select a category/subcategory"
                                   delegate:nil
                          cancelButtonTitle:@"OK"
                          otherButtonTitles:nil] show];
        return;
    }
    
    // Create the image data from the UIImage
    NSData *imageData = UIImageJPEGRepresentation(_imagePreview.image, 90);
    

    // Upload the image
    //[self uploadQuestionForUser:[UserInfo sharedClient].userModel.userId withCategory:_selectedCategory.categoryId withSubcategory:_selectedSubcategory.subcategoryId withDescription:_descriptionText.text withImage:imageData];
    
    // Show the upload progress indicator
    _hud = [MBProgressHUD showHUDAddedTo:self.view animated:YES];
    _hud.mode = MBProgressHUDModeAnnularDeterminate;
    _hud.labelText = @"Uploading Question";
    _hud.removeFromSuperViewOnHide = YES;
}




- (void) uploadQuestionForUser:(NSInteger)userId withCategory:(NSInteger)categoryId withSubcategory:(NSInteger)subcategoryId withDescription:(NSString *)description withImage:(NSData *)imageData;
{
    
    NSDictionary *parameters = @{
                                 @"student_id": @(userId),
                                 @"category_id": @(categoryId),
                                 @"subcategory_id": @(subcategoryId),
                                 @"description": description
                                 };
    
    
    [[Snap2AskClient sharedClient].manager POST:[NSString stringWithFormat:@"%@/questions", Snap2AskApiPath] parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        NSInteger questionId = [[returnedData objectForKey:@"insert_id"] integerValue];
        
        // Upload the question image file
        [self uploadQuestionImage:questionId withImage:imageData];
        
        [[NSNotificationCenter defaultCenter] postNotificationName:RegisterUserNotification object:self userInfo:returnedData];
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        
        NSMutableURLRequest *request =  (NSMutableURLRequest *)operation.request;
        NSString *content =[ NSString stringWithCString:[request.HTTPBody bytes] encoding:NSUTF8StringEncoding];
        NSLog(@"Request: %@",content);
        
        NSLog(@"Error Uploading Image Metadata: %@", error);
    }];
}


- (void) uploadQuestionImage:(NSInteger)questionId withImage:(NSData *)imageData
{
    
        AFHTTPRequestOperation *operation = [[Snap2AskClient sharedClient].manager POST:[NSString stringWithFormat:@"%@/questions/%d", Snap2AskApiPath, questionId] parameters:nil constructingBodyWithBlock:^(id<AFMultipartFormData> formData) {
        [formData appendPartWithFileData:imageData name:@"file" fileName:@"question.jpeg" mimeType:@"image/jpeg"];
    } success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *responseDict = (NSDictionary *) responseObject;
        
        [[NSNotificationCenter defaultCenter] postNotificationName:UploadQuestionImageNotification object:self userInfo:responseDict];
        
        NSLog(@"Response: %@", responseObject);
        
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"Error Uploading Image: %@", error);
    }];
    
    [operation setUploadProgressBlock:^(NSUInteger bytesWritten, long long totalBytesWritten, long long totalBytesExpectedToWrite) {
        CGFloat progress = ((CGFloat)totalBytesWritten) / totalBytesExpectedToWrite;
        [_hud setProgress:progress];
    }];
}

- (IBAction)unwindToAskQuestion:(UIStoryboardSegue *)unwindSegue
{
    // Return from selecting a category
}

- (void) prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    if ([segue.identifier isEqualToString:@"showCategoriesSegue"]) {
        
        SelectCategoryViewController *vc =  (SelectCategoryViewController *)segue.destinationViewController;
        
        vc.categoryData = _categoryData;
    }
}
@end
