//
//  SelectSubcategoryViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 10/14/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "CategoryCell.h"
#import "SubcategoryCell.h"
#import "CategoryModel.h"
#import "SubcategoryModel.h"
#import "AskQuestionViewController.h"

@interface SelectSubcategoryViewController : UITableViewController

@property (strong, nonatomic) NSDictionary* subcategoryData;
@property (strong, nonatomic) CategoryModel* selectedCategory;

@end
