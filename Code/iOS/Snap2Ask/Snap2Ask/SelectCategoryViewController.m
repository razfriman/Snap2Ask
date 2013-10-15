//
//  SelectCategoryViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 10/14/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "SelectCategoryViewController.h"

@interface SelectCategoryViewController ()

@end

@implementation SelectCategoryViewController

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
    return _categoryData.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier = @"categoryCell";
    CategoryCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    cell.titleLabel.text = [[_categoryData allKeys] objectAtIndex:indexPath.row];
    
    return cell;
}

#pragma mark - Navigation

// In a story board-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    if ([segue.identifier isEqualToString:@"showSubcategoriesSegue"]) {
        
        // Get the selected category
        NSString *selectedCategory = ((CategoryCell *) sender).titleLabel.text;
        CategoryModel *category = (CategoryModel *)[_categoryData objectForKey: selectedCategory];
        
        // Assign the subcategory data to the destination view controller
        SelectSubcategoryViewController *vc =  (SelectSubcategoryViewController *)segue.destinationViewController;
        vc.selectedCategory = category;
        vc.subcategoryData = category.subcategories;
    }
}



@end
