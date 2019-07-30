#include <stdlib.h>
#include <stdio.h>
#include <sys/types.h>
#include <errno.h>
#include <unistd.h>
#include <signal.h>
//0. set signal handler
//1. fhild sends signal back to parent

void signal_callback_handler(int signum)
{
	//signal handler for SIGUSR1
	printf("Got signal %d\n",signum);
	exit(0);
}

int main(int argc, char *argv[]) 
{
	pid_t cpid;
	//0. set signal handler
	signal(SIGUSR1, signal_callback_handler);
	
	//1.fork child
	cpid = fork();
	if(cpid == 0){
		//child
		printf("sending sig to parent \n");
		kill(getppid(), SIGUSR1);
		exit(0);
	}
	else if(cpid > 0){
		//parent
		wait(0);
	}
	else{
		//err
		perror("fork\n");
	}

}

