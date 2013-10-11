//
//  AskQuestionViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/26/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "AskQuestionViewController.h"
#import "UIProgressView+AFNetworking.h"


@interface AskQuestionViewController ()

@property (weak, nonatomic) IBOutlet UIImageView *imagePreview;
@property (weak, nonatomic) IBOutlet UITextField *descriptionText;
@property (weak, nonatomic) IBOutlet UILabel *categoryLabel;
@property (weak, nonatomic) IBOutlet UILabel *subcategoryLabel;
@property (weak, nonatomic) IBOutlet UIButton *askButton;

@property (nonatomic) BOOL hasSelectedImage;

@property (weak, nonatomic) CategoryModel *selectedCategory;
@property (weak, nonatomic) SubcategoryModel *selectedSubcategory;
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

// TODO:
// REDO DISPLAYING THE UIPICKER VIEW!!!
// USE: ACTIONSHEET?

- (void)viewDidLoad
{
    [super viewDidLoad];

    _hasSelectedImage = NO;
    
    _mediaPicker = [[UIImagePickerController alloc] init];
    _mediaPicker.delegate = self;
    _mediaPicker.allowsEditing = YES;
    
    // Init the pickers
    //self.categoryPicker = [[UIPickerView alloc] initWithFrame:CGRectZero];
    //self.categoryPicker = [[UIPickerView alloc] initWithFrame:CGRectMake(0,40, 320, 216)];
    //self.subcategoryPicker = [[UIPickerView alloc] initWithFrame:CGRectZero];
    
    // Set delegates and datasource to self
    //self.categoryPicker.delegate = self;
    //self.categoryPicker.dataSource = self;
    //self.subcategoryPicker.delegate = self;
    //self.subcategoryPicker.dataSource = self;
    
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

    // Refresh the pickers
    [self.categoryPicker reloadAllComponents];
    [self.subcategoryPicker reloadAllComponents];
}

- (void) questionUploaded:(NSNotification *)notification
{
    UIAlertView *alertView = [[UIAlertView alloc]
                              initWithTitle:@"Success"
                              message:@"Your question has been posted."
                              delegate:nil
                              cancelButtonTitle:@"OK"
                              otherButtonTitles:nil];
    [alertView show];
    
    [self finishSubmitQuestion];
}


- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - UIPickerView Data
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView
{
    if (pickerView == _categoryPicker) {
        return 1;
    } else {
        return 1;
    }
}

- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component
{
    if (pickerView == _categoryPicker) {
        return _categoryData.count;
    } else {
        return [[_categoryData objectForKey:_categoryLabel.text] count];
    }
}
-(NSString *)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component
{
    if (pickerView == _categoryPicker) {
        NSString *key = (NSString *) [[_categoryData allKeys] objectAtIndex:row];
        return key;
    } else {
        NSMutableDictionary *subcategories = (NSMutableDictionary *) [_categoryData objectForKey:_categoryLabel.text];

        NSString *key = (NSString *) [[subcategories allKeys] objectAtIndex:row];
        return  key;
    }
}

-(void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component
{
    if(pickerView == _categoryPicker) {
        NSString *key = (NSString *) [[_categoryData allKeys] objectAtIndex:row];
        _categoryLabel.text = key;
    } else if (pickerView == _subcategoryPicker) {
        
        // TODO: Store this as a variable in AskQuestionViewController to avoid reloading every time the subcategory changes
        // TODO: Update this every time the category changes
        NSMutableDictionary *subcategories = (NSMutableDictionary *) [_categoryData objectForKey:_categoryLabel.text];
        
        NSString *key = (NSString *) [[subcategories allKeys] objectAtIndex:row];
        _subcategoryLabel.text = key;
    }
}
*/

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return 5;
}

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row == 2) {
        // Select Category

        // TODO: Show UIPickerView in UIActionSheet
        
        [tableView deselectRowAtIndexPath:indexPath animated:YES];
        
    } else if (indexPath.row == 3) {
        // Select Subcategory
        
        // TODO: Show UIPickerView in UIActionSheet
        
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
        
        UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:nil delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Take photo", @"Choose Existing", nil];
        
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
    [self updateInput];
    
    [self dismissViewControllerAnimated:YES completion:^{ }];
}

- (void)imagePickerControllerDidCancel:(UIImagePickerController *)picker
{
    [self dismissViewControllerAnimated:YES completion:^{ }];
}

- (void) updateInput
{
    
    // TODO: use this when categories work correctly
    //BOOL didInputFields = [_descriptionText.text isEqualToString:@""] && _selectedCategory && _selectedSubcategory;
    
    // FOR DEBUGGING
    BOOL didInputFields = [_descriptionText.text isEqualToString:@""];
    
    [_askButton setEnabled:(_hasSelectedImage && didInputFields)];
}

- (void) finishSubmitQuestion {
    
    // Add the image to the "My Questions" tab
    // TODO via Notification ::OR:: refresh the controller automatically
    
    // Move to the "My Questions" tab
    [self.tabBarController setSelectedIndex:0];
    
    // Clear the current data
    _imagePreview.image = [UIImage imageNamed:@"Placeholder"];
    _descriptionText.text = @"";
    _categoryLabel.text = @"Select a category";
    _subcategoryLabel.text = @"Select a subcategory";
    _hasSelectedImage = NO;
    
    if (_hud) {
        // Hide the progress HUD
        [_hud hide:YES];
    }
    
}

- (IBAction)submitQuestion:(id)sender {
    
    
    // TODO: CHECK DESC/CATEGORY/SUBCATEGORY FIRST!!!
    

    
    
    NSData *imageData = UIImageJPEGRepresentation(_imagePreview.image, 90);
    

    [self uploadQuestionForUser:[UserInfo sharedClient].userModel.userId withCategory:_selectedCategory.categoryId withSubcategory:_selectedSubcategory.subcategoryId withDescription:_descriptionText.text withImage:imageData];
    
    _hud = [MBProgressHUD showHUDAddedTo:self.view animated:YES];
    _hud.mode = MBProgressHUDModeAnnularDeterminate;
    _hud.labelText = @"Uploading Question";
    _hud.removeFromSuperViewOnHide = YES;
}




- (void) uploadQuestionForUser:(NSInteger)userId withCategory:(NSInteger)categoryId withSubcategory:(NSInteger)subcategoryId withDescription:(NSString *)description withImage:(NSData *)imageData;
{
    
    categoryId = 1;
    subcategoryId = 1;
    
    NSDictionary *parameters = @{
                                 @"student_id": @(userId),
                                 @"category_id": @(categoryId),
                                 @"subcategory_id": @(subcategoryId),
                                 @"description": description
                                 };
    
    
    [[Snap2AskClient sharedClient].manager POST:@"/snap2ask/api/index.php/questions" parameters:parameters success:^(AFHTTPRequestOperation *operation, id responseObject) {
        
        NSDictionary *returnedData = (NSDictionary *) responseObject;
        
        NSInteger questionId = [[returnedData objectForKey:@"insert_id"] integerValue];
        
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
    
        AFHTTPRequestOperation *operation = [[Snap2AskClient sharedClient].manager POST:[NSString stringWithFormat:@"/snap2ask/api/index.php/questions/%d",questionId] parameters:nil constructingBodyWithBlock:^(id<AFMultipartFormData> formData) {
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
@end
