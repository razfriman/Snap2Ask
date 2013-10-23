//
//  QuestionDetailsViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/27/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "QuestionDetailsViewController.h"
#import "QuestionDetailCell.h"
#import "AnswerDetailCell.h"

@interface QuestionDetailsViewController ()


// Where does this go?
@property (strong, nonatomic) IBOutlet UITapGestureRecognizer *imageTapRecognizer;

@end

@implementation QuestionDetailsViewController

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

- (NSString *)tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section{
    if (section == 0) {
        return @"Question";
    } else {
        return @"Answers";
    }
}

- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section == 0) {
        // Question height
        return 428;
    } else if (indexPath.section == 1) {
        // Answer height
        return 180;
    } else {
        // Default height
        return 30;
    }
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    if (_question.answers.count > 0) {
        return 2;
    } else {
        return 1;
    }
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    if(section == 0) {
        return 1;
    } else if (section == 1) {
        return _question.answers.count;
    } else {
        return 0;
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *QuestionCellIdentifier = @"questionCell";
    static NSString *AnswerCellIdentifier = @"answerCell";
    
    if (indexPath.section == 0) {
        QuestionDetailCell *cell = [self.tableView dequeueReusableCellWithIdentifier:QuestionCellIdentifier forIndexPath:indexPath];
        
        cell.categoryLabel.text = [NSString stringWithFormat:@"Category: %@", _question.category];
        cell.subcategoryLabel.text = [NSString stringWithFormat:@"Subcategory: %@", _question.subcategory];
        cell.descriptionLabel.text = [NSString stringWithFormat:@"Description: %@", _question.description];
        
        [cell.imageView setImageWithURL:[NSURL URLWithString:_question.imageUrl] placeholderImage:[UIImage imageNamed:@"Placeholder"]];
        
        // Add image tap recongnizer?
        
        
        return cell;
    } else if (indexPath.section == 1) {
        AnswerDetailCell *cell = [self.tableView dequeueReusableCellWithIdentifier:AnswerCellIdentifier forIndexPath:indexPath];
        
        AnswerModel *answer = [_question.answers objectAtIndex:indexPath.row];
        cell.tutorLabel.text = [NSString stringWithFormat:@"Tutor: %@ %@", answer.tutor.firstName, answer.tutor.lastName];
        cell.tutorRankLabel.text = [NSString stringWithFormat:@"Rating: %d", 1];
        cell.answerLabel.text = [NSString stringWithFormat:@"%@", answer.text];

        return cell;
    }
    
    return nil;
}

- (void) prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender
{
    if ([segue.identifier isEqualToString:@"imageDetailsSegue"]) {
        
        ImageDetailsViewController *detailViewController = [segue destinationViewController];
        
        detailViewController.imageUrl = _question.imageUrl;
    }
}

- (IBAction)imageDetailsTap:(id)sender {
    [self performSegueWithIdentifier:@"imageDetailsSegue" sender:self];
}

@end
