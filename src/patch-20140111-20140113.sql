---- -----------------------------------------------------------------------------
-- patch-20140111-20140113.sql
--  !!! Experimental Patch !!!
--
--  add a column u_is_super in table user for whitelist replacement
--  without affecting current score
-- 
-- @author: WarLocG <warlocg.wlg@gmail.com>
-- -------------------------------------------------------------------------------

-- -------------------------------------------------------------------------------
-- ALTER clauses
-- -------------------------------------------------------------------------------

-- ToDo : Make possible a change detection to add a IF (EXISTS) clause maybe
--        with PRAGMA user_version

-- Add column `u_is_super` as Integer after `u_score`
-- !!! Comment the line below if the column already exists !!!
ALTER TABLE user ADD u_is_super INTEGER default 0;

-- -------------------------------------------------------------------------------
-- UPDATE clauses
-- -------------------------------------------------------------------------------

-- Update user with privileges
UPDATE user SET u_is_super = 1 WHERE u_pseudo IN ('Meier_Link','meier_link','WarLocG');

-- -------------------------------------------------------------------------------
-- INSERT clauses
-- -------------------------------------------------------------------------------

-- Insert super users in user table if not exists (need u_pseudo is unique)
INSERT OR IGNORE INTO user (u_pseudo, u_start, u_score, u_is_super) VALUES 
  ("Meier_Link", DATETIME('NOW'), 0, 1);
INSERT OR IGNORE INTO user (u_pseudo, u_start, u_score, u_is_super) VALUES 
  ("meier_link", DATETIME('NOW'), 0, 1);
INSERT OR IGNORE INTO user (u_pseudo, u_start, u_score, u_is_super) VALUES 
  ("WarLocG", DATETIME('NOW'), 0, 1);
