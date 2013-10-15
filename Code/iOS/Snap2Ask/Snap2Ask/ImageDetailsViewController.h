//
//  ImageDetailsViewController.h
//  Snap2Ask
//
//  Created by Raz Friman on 10/12/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SDWebImage/UIImageView+WebCache.h"

@interface ImageDetailsViewController : UIViewController<UIScrollViewDelegate>

@property (strong, nonatomic) NSString *imageUrl;

@end
