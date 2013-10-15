//
//  SelectSubcategoryViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 10/14/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "SelectSubcategoryViewController.h"

@interface SelectSubcategoryViewController ()

@end

@implementation SelectSubcategoryViewController

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
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    // Return the number of sections.
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    // Return the number of rows in the section.
    return _subcategoryData.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier = @"subcategoryCell";
    SubcategoryCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    cell.titleLabel.text = [[_subcategoryData allKeys] objectAtIndex:indexPath.row];
    
    return cell;
}

- (void) prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    SubcategoryCell *selectedCell = (SubcategoryCell *) sender;
    
    // Get the category data
    CategoryModel *category = _selectedCategory;
    SubcategoryModel *subcategory = [_subcategoryData objectForKey:selectedCell.textLabel.text];
    
    // Update the destination view controller with the selected category
    AskQuestionViewController *root = segue.destinationViewController;
    [root updateSelectedCategory:category withSubcategory:subcategory];
}

@end
