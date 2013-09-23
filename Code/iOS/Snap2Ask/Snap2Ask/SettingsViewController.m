//
//  SettingsViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/28/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "SettingsViewController.h"

@interface SettingsViewController ()

@end

@implementation SettingsViewController

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

    self.clearsSelectionOnViewWillAppear = NO;
    
    [self.refreshControl addTarget:self action:@selector(refreshView:) forControlEvents:UIControlEventValueChanged];
    // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
    // self.navigationItem.rightBarButtonItem = self.editButtonItem;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 3;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    switch (section) {
        case 0:
            return 3;
        case 1:
            return 3;
        case 2:
            return 1;
        default:
            return 0;
    }
}


- (void) prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    if ([segue.identifier isEqualToString:@"unwindToMainSegue"]) {
        
        // TODO
        // LOG OUT CODE HERE
        // CUSTOM/FACBEOOK/GOOGLE
    }
}

- (void) refreshView:(UIRefreshControl*) sender
{
    // Pull-To-Refresh
    
    // UPDATE DATA FROM API CLIENT (JSON)
    
    //int userId = 10;
    //[[Snap2AskClient sharedClient] getQuestionsUsingArray:_questions ForUser:userId];
    
    [sender endRefreshing];
    
}


@end
