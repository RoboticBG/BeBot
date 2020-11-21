import rospy
from geometry_msgs.msg import Twist, Point, Quaternion
#import tf
from math import radians
#, copysign, sqrt, pow, pi, atan2
#from tf.transformations import euler_from_quaternion
#import numpy as np
import sys
import time
#import math

class Run():
    def __init__(self):
        prog_start_time = time.time()
        rospy.init_node('turtlebot3_pointop_key', anonymous=False)
        self.cmd_vel = rospy.Publisher('cmd_vel', Twist, queue_size=5)
        position = Point()
        move_cmd = Twist()
        linear_dist = float(sys.argv[1])
        init_speed=0.15
        move_cmd.linear.x = 0.0
        move_cmd.angular.z = 0.0
        self.cmd_vel.publish(move_cmd)

        if linear_dist > 0.0:
            move_cmd.linear.x = init_speed
        elif linear_dist == 0:
       	    move_cmd.linear.x = 0.0
        else:
            move_cmd.linear.x = -init_speed
        delay_lin = abs(linear_dist/move_cmd.linear.x)
        start_time = time.time()
        print("delay_lin", delay_lin)
        time.sleep(0.03)

        while time.time()-start_time < delay_lin:
                self.cmd_vel.publish(move_cmd)
                time.sleep(0.03)
        move_cmd.linear.x = 0.0
        move_cmd.angular.z = 0.0
        self.cmd_vel.publish(move_cmd)
        print("TOTAL TIME: ",time.time()-start_time)

if __name__ == '__main__':
    Run()
