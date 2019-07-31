
public class Tree {

	private int value;
	private Tree left;
	private Tree right;
	
	public Tree(int v) {
		value = v;
		left = null;
		right = null;
	}
	
	public void add(int new_val) {
		if (new_val < value) {
			// new_val is smaller than value
			// check left side
			if (left == null) {
				left = new Tree(new_val);
			} else {
				left.add(new_val);
			}
		} else if (new_val > value) {
			// new_val is larger than value
			// check right side
			if (right == null) {
				right = new Tree(new_val);
			} else {
				right.add(new_val);
			}
		}
	}
	
	
	
	public String toString()	{
		String str = Integer.valueOf(value).toString() + " ";
		if (left != null) {
			str = left.toString() + str;
		}
		if (right != null) {
			str = str + right.toString();
		}
		
		return str;
	}
}








