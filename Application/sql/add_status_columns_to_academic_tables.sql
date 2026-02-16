-- Add status columns to academic_books table
ALTER TABLE academic_books
ADD COLUMN IF NOT EXISTS approved TINYINT(1) DEFAULT 0 COMMENT '1 = Approved, 0 = Not Approved',
ADD COLUMN IF NOT EXISTS aligned TINYINT(1) DEFAULT 0 COMMENT '1 = Aligned, 0 = Not Aligned',
ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT NULL COMMENT 'Status: Approved, Aligned, Pending, etc.';

-- Add indexes for better query performance
CREATE INDEX IF NOT EXISTS idx_approved_books ON academic_books(approved);
CREATE INDEX IF NOT EXISTS idx_aligned_books ON academic_books(aligned);
CREATE INDEX IF NOT EXISTS idx_status_books ON academic_books(status);
