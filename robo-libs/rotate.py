import rospy
from geometry_msgs.msg import Twist, Point, Quaternion
#import tf
#from math import math,radians, copysign, sqrt, pow, pi, atan2
#from tf.transformations import euler_from_quaternion
#import numpy as np
import sys
import time
import math

class Run():
    def __init__(self):
        prog_start_time = time.time()
        rospy.init_node('turtlebot3_pointop_key', anonymous=False)
        self.cmd_vel = rospy.Publisher('cmd_vel', Twist, queue_size=5)
        position = Point()
        move_cmd = Twist()
        angular_dist = float(sys.argv[1])
        move_cmd.linear.x = 0.0
        move_cmd.angular.z = 0.0
        init_speed=0.8
        self.cmd_vel.publish(move_cmd)

        if angular_dist > 0.0:
            move_cmd.angular.z = init_speed
        elif angular_dist == 0:
       	    move_cmd.angular.z = 0.0
        else:
       	    move_cmd.angular.z = -init_speed

        delay_angle = math.radians(abs(angular_dist))/init_speed
        start_time = time.time()
        print("delay_angle", delay_angle)
        time.sleep(0.03)

        while time.time()-start_time < delay_angle:
            self.cmd_vel.publish(move_cmd)
            time.sleep(0.03)
        move_cmd.linear.x = 0.0
        move_cmd.angular.z = 0.0
        self.cmd_vel.publish(move_cmd)
        print("TOTAL TIME: ",time.time()-start_time)

if __name__ == '__main__':
    Run()
