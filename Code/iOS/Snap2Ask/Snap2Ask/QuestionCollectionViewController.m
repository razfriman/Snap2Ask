//
//  QuestionCollectionViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/25/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "QuestionCollectionViewController.h"
#import "QuestionDetailsViewController.h"
#import "QuestionCell.h"
#import "SDWebImage/UIImageView+WebCache.h"
#import "QuestionModel.h"
#import "Snap2AskClient.h"

NSString *kCellID = @"cellID";

@interface QuestionCollectionViewController ()

@property (weak, nonatomic) IBOutlet UISegmentedControl *segmentControl;
@property (strong, nonatomic) UIRefreshControl *refreshControl;
@property(nonatomic, strong) NSMutableArray *questions;

@end

@implementation QuestionCollectionViewController

- (IBAction)segmentChanged:(id)sender {
    [self.collectionView reloadData];
}

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // Do any additional setup after loading the view.
    
    self.questions = [@[] mutableCopy];
    
    // Register for notifications
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(questionsUpdated:) name:QuestionsNotification object:nil];
    
    // Add RefreshControl
    self.refreshControl = [[UIRefreshControl alloc] init];
    [self.collectionView addSubview:self.refreshControl];
    self.collectionView.alwaysBounceVertical = YES;
    [self.refreshControl addTarget:self action:@selector(refreshView:) forControlEvents:UIControlEventValueChanged];
    
    // Init sample questions
    QuestionModel* question1 = [[QuestionModel alloc] init];
    question1.isAnswered = YES;
    question1.category = @"Math";
    question1.subcategory = @"Calculus";
    question1.description = @"What is the derivative of x^2";
    question1.imageThumbnailUrl = @"http://upload.wikimedia.org/wikipedia/en/e/e5/NBATV_HWC_115x115.png";
    question1.imageUrl = @"http://upload.wikimedia.org/wikipedia/en/e/e5/NBATV_HWC_115x115.png";

    QuestionModel* question2 = [[QuestionModel alloc] init];
    question2.isAnswered = NO;
    question2.category = @"Science";
    question2.subcategory = @"Chemistry";
    question2.description = @"What is Fe?";
    question2.imageThumbnailUrl = @"http://upload.wikimedia.org/wikipedia/en/e/e5/NBATV_HWC_115x115.png";
    question2.imageUrl = @"http://upload.wikimedia.org/wikipedia/en/e/e5/NBATV_HWC_115x115.png";

    // Add sample questions
    [_questions addObject:question1];
    [_questions addObject:question2];
    
    // Update collection
	[self.collectionView reloadData];
}

- (void) questionsUpdated:(NSNotification *)notification
{
    [self.collectionView reloadData];
}

- (void) refreshView:(UIRefreshControl*) sender
{
    // Pull-To-Refresh
    
    // UPDATE DATA FROM API CLIENT (JSON)
    
    int userId = 10;
    
    [[Snap2AskClient sharedClient] getQuestionsUsingArray:_questions ForUser:userId];
    
    [sender endRefreshing];
    
}

- (NSInteger)collectionView:(UICollectionView *)view numberOfItemsInSection:(NSInteger)section;
{
    NSArray *filteredQuestions = [self getFilteredQuestions];
    
    return filteredQuestions.count;
}

-(NSArray *) getFilteredQuestions {
    if(_segmentControl.selectedSegmentIndex == 0) {
        
        // Answered
        NSPredicate *predicate = [NSPredicate predicateWithFormat:@"isAnswered==YES"];
        NSArray *arr = [_questions filteredArrayUsingPredicate:predicate];
        return arr;
    } else if (_segmentControl.selectedSegmentIndex == 1) {
        
        // Unanswered
        NSPredicate *predicate = [NSPredicate predicateWithFormat:@"isAnswered==NO"];
        NSArray *arr = [_questions filteredArrayUsingPredicate:predicate];
        return  arr;
    } else if (_segmentControl.selectedSegmentIndex == 2) {
        // All
        return  _questions;
    } else {
        return @[];
    }
}

- (UICollectionViewCell *)collectionView:(UICollectionView *)cv cellForItemAtIndexPath:(NSIndexPath *)indexPath;
{
    
    NSArray *filteredQuestions = [self getFilteredQuestions];
    QuestionModel* question = [filteredQuestions objectAtIndex:indexPath.row];
    
    QuestionCell *cell = [cv dequeueReusableCellWithReuseIdentifier:kCellID forIndexPath:indexPath];
    
    cell.label.text = question.category;
    
    [cell.image setImageWithURL:[NSURL URLWithString:question.imageThumbnailUrl]
               placeholderImage:[UIImage imageNamed:@"Camera"]];
    
    return cell;
}

- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    if ([[segue identifier] isEqualToString:@"showDetail"])
    {
        QuestionDetailsViewController *detailViewController = [segue destinationViewController];
        
        NSArray *arr = [self getFilteredQuestions];
        
        NSIndexPath *selectedIndexPath = [[self.collectionView indexPathsForSelectedItems] objectAtIndex:0];
        int row = selectedIndexPath.row;
        
        detailViewController.question = arr[row];
    }
}


@end
