public class test{
	   public static void rectangle(int h,int w){
	   		for(int i = 1; i<=h; i++){
				for(int j=1; j<=w;j++){
				System.out.print("#");
			}
			if(i !=w){
				System.out.println();
			}
	   }
	   }
	   public static void main(String[] args){
	   		rectangle(3,10);
	   }
}
