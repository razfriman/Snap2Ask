//
//  Utilities.m
//  Snap2Ask
//
//  Created by Raz Friman on 9/23/13.
//  Copyright (c) 2013 Raz Friman. All rights reserved.
//


#import "Utilities.h"

@implementation NSDictionary (Utility)

// in case of [NSNull null] values a nil is returned ...
- (id)objectForKeyNotNull:(id)key
{
    id object = [self objectForKey:key];
    
    if (object == [NSNull null])
    {
        return nil;
    }
    
    return object;
}

@end