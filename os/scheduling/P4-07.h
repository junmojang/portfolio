/*	================== emptyqueue =================
This algorithm checks to see if queue is empty
Pre    queue is a pointer to a queue head node
Return true if empty; false if queue has data
*/
bool emptyqueue(queue* pqueue)
{
	//	Statements 
	return (pqueue->count == 0);
}	// emptyqueue 