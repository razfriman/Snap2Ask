//
//  QuestionTableViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/30/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "QuestionTableViewController.h"

@interface QuestionTableViewController ()

@property (weak, nonatomic) IBOutlet UISegmentedControl *segmentControl;

@end

@implementation QuestionTableViewController

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
    
    self.questions = [@[] mutableCopy];

    // Register refresh control's selector
    [self.refreshControl addTarget:self action:@selector(refreshView:) forControlEvents:UIControlEventValueChanged];
    
    // Register for notifications
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(questionsUpdated:) name:QuestionsNotification object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(questionDeleted:) name:QuestionDeletedNotification object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(newQuestionSubmitted:) name:NewQuestionSubmittedNotification object:nil];
    
    // Load Questions
    int userId = [[UserInfo sharedClient] userModel].userId;
    [[Snap2AskClient sharedClient] loadQuestionsForUser:userId];
    
    self.clearsSelectionOnViewWillAppear = YES;
 
    self.navigationItem.rightBarButtonItem = self.editButtonItem;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void) questionsUpdated:(NSNotification *)notification
{
    self.questions = [notification.userInfo objectForKey:@"questions"];
    [self.tableView reloadData];
}

- (void) newQuestionSubmitted:(NSNotification *)notification
{
    QuestionModel *question = [[QuestionModel alloc] initWithJSON:notification.userInfo];
    [self.questions addObject:question];
    
    [self.tableView reloadData];
}

- (void) questionDeleted:(NSNotification *)notification
{
    [self.tableView reloadData];
}


- (IBAction)updateSegment:(id)sender {
    [self.tableView reloadData];
}

- (void) refreshView:(UIRefreshControl*) sender
{
    // Pull-To-Refresh
    
    int userId = [[UserInfo sharedClient] userModel].userId;
    [[Snap2AskClient sharedClient] loadQuestionsForUser:userId];
    
    [sender endRefreshing];
}


#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    NSArray *filteredQuestions = [self getFilteredQuestions];
    
    return filteredQuestions.count;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier = @"tableCell";
    
    NSArray *filteredQuestions = [self getFilteredQuestions];
    QuestionModel* question = [filteredQuestions objectAtIndex:indexPath.row];
    
    QuestionCell *cell = [self.tableView dequeueReusableCellWithIdentifier:CellIdentifier forIndexPath:indexPath];
    
    cell.category.text = [NSString stringWithFormat:@"%@", question.category];
    cell.subcategory.text = [NSString stringWithFormat:@"%@", question.subcategory];
    cell.description.text = question.description;
    [cell.isAnsweredLabel setHidden:question.status == 0];
    
    [cell.thumbnailView setImageWithURL:[NSURL URLWithString:question.imageThumbnailUrl] placeholderImage:[UIImage imageNamed:@"Placeholder"]];
    
    return cell;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 115;
}

- (void) tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    NSArray *filteredQuestions = [self getFilteredQuestions];
    
    QuestionModel *question = [filteredQuestions objectAtIndex:indexPath.row];
    
    if (question) {
        int questionId = question.questionId;
        
        // Delete the question from the DB
        [[Snap2AskClient sharedClient] deleteQuestion:questionId];
        
        // Find the question in the questions array
        for (int i = 0; i < _questions.count; i++) {
            if (((QuestionModel *)[_questions objectAtIndex:i]).questionId == questionId) {

                // Remove from the questions array
                [_questions removeObjectAtIndex:i];
            }
        }
        
    }
    
}


-(NSArray *) getFilteredQuestions {
    if(_segmentControl.selectedSegmentIndex == 0) {
        
        // Answered
        NSPredicate *predicate = [NSPredicate predicateWithFormat:@"status>0"];
        NSArray *arr = [_questions filteredArrayUsingPredicate:predicate];
        return arr;
    } else if (_segmentControl.selectedSegmentIndex == 1) {
        
        // Unanswered
        NSPredicate *predicate = [NSPredicate predicateWithFormat:@"status==0"];
        NSArray *arr = [_questions filteredArrayUsingPredicate:predicate];
        return  arr;
    } else if (_segmentControl.selectedSegmentIndex == 2) {
        // All
        return  _questions;
    } else {
        return @[];
    }
}

- (BOOL) shouldPerformSegueWithIdentifier:(NSString *)identifier sender:(id)sender
{
    if ([identifier isEqualToString:@"showDetail"])
    {
        // Only show the question details if the table is not in edit mode
        return !self.tableView.isEditing;
    }
    
    return YES;
}

// In a story board-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    if ([[segue identifier] isEqualToString:@"showDetail"])
    {
    QuestionDetailsViewController *detailViewController = [segue destinationViewController];
    
        NSArray *arr = [self getFilteredQuestions];
        
        NSIndexPath *selectedIndexPath = [self.tableView indexPathForSelectedRow];
        int row = selectedIndexPath.row;
        
        detailViewController.question = [arr objectAtIndex:row];
    }
}

@end
