//
//  SelectCategoryViewController.h
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
#import "SelectSubcategoryViewController.h"

@interface SelectCategoryViewController : UITableViewController<UITableViewDataSource,UITableViewDelegate>

@property (strong, nonatomic) NSDictionary* categoryData;

@end
