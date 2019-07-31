import java.util.HashMap;

/**
 * 
 */

/**
 * @author DKU
 *
 */
public class Symbol implements Comparable<Symbol> {
	char ch;
	int frequency;
	String codeword;
	
	Symbol(char c, int freq) {
		ch = c;
		frequency = freq;
		codeword = "";
		
		left = null;
		right = null;
	}

	@Override
	public int compareTo(Symbol o) {
		// TODO Auto-generated method stub
		return this.frequency - o.frequency;
	}

	Symbol left;
	Symbol right;
	
	public static Symbol buildUp(Symbol cleft, Symbol cright) {
		Symbol s = new Symbol((char)0, 
				cleft.frequency + cright.frequency);
		s.left = cleft;
		s.right = cright;
		
		return s;
	}
	
	public void labeling(String prefix) {
		if (this.left != null) {
			left.labeling(prefix+"0");
		}
		if (this.right != null) {
			right.labeling(prefix+"1");
		}
		if (left == null && right == null) {
			this.codeword = prefix;
			HuffmanTable.put(this.ch, this.codeword);
		}
	}

	static HashMap<Character,String> HuffmanTable = 
			new HashMap<Character,String>();
	
	public String toString() {
		String str = new String(
		"Symbol info ch: " + this.ch + " freq: " + this.frequency + 
		" codeword: " + this.codeword);
		return str;
	}

}

















