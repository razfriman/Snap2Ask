//
//  ImageDetailsViewController.m
//  Snap2Ask
//
//  Created by Raz Friman on 10/12/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import "ImageDetailsViewController.h"

@interface ImageDetailsViewController ()
@property (weak, nonatomic) IBOutlet UIImageView *imageView;
@property (weak, nonatomic) IBOutlet UIScrollView *scrollView;

@end

@implementation ImageDetailsViewController

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
    

        // Load the image
    [_imageView setImageWithURL:[NSURL URLWithString:_imageUrl] placeholderImage:[UIImage imageNamed:@"Placeholder"] completed:^(UIImage *image, NSError *error, SDImageCacheType cacheType) {
        
        
        // Set the zooming properties
        _imageView.frame = _scrollView.bounds;
        _scrollView.minimumZoomScale = 1.0  ;
        _scrollView.maximumZoomScale = _imageView.image.size.width / _scrollView.frame.size.width;
        _scrollView.zoomScale = 1.0;
        _scrollView.delegate = self;
    }];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (UIView *) viewForZoomingInScrollView:(UIScrollView *)scrollView
{
    // Set the view that the UIScrollView will zoom with
    return self.imageView;
}
@end
