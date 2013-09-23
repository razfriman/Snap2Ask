//
//  AskQuestionViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/26/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "AskQuestionViewController.h"

@interface AskQuestionViewController ()

@property (weak, nonatomic) IBOutlet UIImageView *imagePreview;
@property (weak, nonatomic) IBOutlet UITextField *descriptionText;
@property (weak, nonatomic) IBOutlet UITextField *categoryText;
@property (weak, nonatomic) IBOutlet UIButton *askButton;

@property (strong, nonatomic) IBOutlet UITapGestureRecognizer *imageTapRecognizer;

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

    // Preserve selection between presentations.
    self.clearsSelectionOnViewWillAppear = NO;
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

- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    if(textField == _descriptionText) {
        //[_categoryText becomeFirstResponder];
        [_descriptionText resignFirstResponder];
    } else if(textField == _categoryText) {
        [_categoryText resignFirstResponder];
    }
    
    return YES;
}


- (IBAction)tapImageView:(id)sender {
    [self selectPhoto:sender];
}

- (IBAction)selectPhoto:(id)sender {
    
    // Resign both UITextFields
    [_descriptionText resignFirstResponder];
    [_categoryText resignFirstResponder];
    
    _mediaPicker = [[UIImagePickerController alloc] init];
    [_mediaPicker setDelegate:self];
    _mediaPicker.allowsEditing = YES;
    
    if ([UIImagePickerController isSourceTypeAvailable:UIImagePickerControllerSourceTypeCamera]) {
        
        UIActionSheet *actionSheet = [[UIActionSheet alloc] initWithTitle:nil delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Take photo", @"Choose Existing", nil];
        
        [actionSheet showInView:self.view];
    }
    else {
        _mediaPicker.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
        [self presentViewController:_mediaPicker animated:YES completion:NULL];
    }
    
}

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

- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
    UIImage *img = [info objectForKey:UIImagePickerControllerEditedImage];
    if (!img) img = [info objectForKey:UIImagePickerControllerOriginalImage];
    
    _imagePreview.image = img;
    
    [self dismissViewControllerAnimated:YES completion:^{ }];
}

- (void)imagePickerControllerDidCancel:(UIImagePickerController *)picker
{
    [self dismissViewControllerAnimated:YES completion:^{ }];
}

- (void) finishSubmitQuestion {
    
    UITabBarController *parent = (UITabBarController *) self.parentViewController;
    
    // Move to the "My Questions" tab
    [parent setSelectedIndex:0];
    
    
    // Clear the current data
    _imagePreview.image = [UIImage imageNamed:@"Placeholder"];
    _descriptionText.text = @"";
    _categoryText.text = @"";
}

- (IBAction)submitQuestion:(id)sender {
    

    UIAlertView *alertView = [[UIAlertView alloc]
                              initWithTitle:@"Success"
                              message:@"Your question has been posted."
                              delegate:nil
                              cancelButtonTitle:@"OK"
                              otherButtonTitles:nil];
    [alertView show];
    
    [self finishSubmitQuestion];

}

/*
#pragma mark - Navigation

// In a story board-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}

 */

@end
